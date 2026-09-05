<?php

namespace App\Actions\Purchases;

use App\Models\Purchase;
use App\Models\AccountPayable;
use App\Enums\PurchaseStatus;
use App\Enums\PayableStatus;
use App\Enums\PurchaseType;
use App\Context\CompanyContext;
use App\Services\ModuleManager;
use App\Services\Purchases\PurchaseNumberGenerator;
use App\Services\Purchases\CalculatePurchaseTotals;
use Illuminate\Support\Facades\DB;
use Exception;

class ConfirmPurchase
{
    public function execute(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {
            $company = app(CompanyContext::class)->getCompany();
            $moduleManager = app(ModuleManager::class);

            if ($purchase->company_id !== $company->id) {
                throw new Exception("La compra no pertenece a la empresa activa.");
            }

            if (!$moduleManager->isActive($company, 'purchases')) {
                throw new Exception("El módulo de compras no está activo.");
            }

            if ($purchase->status !== PurchaseStatus::DRAFT) {
                throw new Exception("Solo las compras en borrador pueden ser confirmadas.");
            }

            if ($purchase->items()->count() === 0) {
                throw new Exception("La compra no tiene líneas de detalle.");
            }

            // Recalculate totals just to be safe
            $purchase->load('items.product');
            app(CalculatePurchaseTotals::class)->execute($purchase);

            // Generate Number
            $purchase->number = app(PurchaseNumberGenerator::class)->generate($company->id);

            $inventoryActive = $moduleManager->isActive($company, 'inventory');

            foreach ($purchase->items as $item) {
                if ($item->product_id && $item->product) {
                    if ($inventoryActive && $item->product->track_inventory) {
                        if (!$purchase->warehouse_id) {
                            throw new Exception("Se requiere un almacén para registrar productos inventariables.");
                        }

                        // Register inventory entry
                        app(\App\Services\Inventory\InventoryService::class)->registerEntry(
                            $purchase->warehouse,
                            $item->product,
                            $item->quantity,
                            Purchase::class,
                            $purchase->id,
                            "Entrada por compra {$purchase->number}"
                        );
                    }
                }
                $item->save();
            }

            $purchase->status = PurchaseStatus::CONFIRMED;
            $purchase->confirmed_at = now();

            if ($purchase->purchase_type === PurchaseType::CREDIT) {
                AccountPayable::create([
                    'company_id' => $company->id,
                    'supplier_id' => $purchase->supplier_id,
                    'purchase_id' => $purchase->id,
                    'original_amount' => $purchase->total,
                    'paid_amount' => 0,
                    'balance' => $purchase->total,
                    'issued_at' => $purchase->purchase_date,
                    'due_date' => $purchase->due_date ?? now(),
                    'status' => PayableStatus::PENDING,
                ]);
            } else {
                // If it is a cash purchase, it's considered paid immediately.
                // In a more complex system, we'd record the cash movement here.
                $purchase->paid_amount = $purchase->total;
                $purchase->balance = 0;
                $purchase->status = PurchaseStatus::PAID;
            }

            $purchase->save();
        });
    }
}
