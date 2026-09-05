<?php

namespace App\Actions\Purchases;

use App\Models\Purchase;
use App\Enums\PurchaseStatus;
use App\Enums\PayableStatus;
use App\Context\CompanyContext;
use App\Services\ModuleManager;
use Illuminate\Support\Facades\DB;
use Exception;

class CancelPurchase
{
    public function execute(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {
            $company = app(CompanyContext::class)->getCompany();

            if ($purchase->company_id !== $company->id) {
                throw new Exception("La compra no pertenece a la empresa activa.");
            }

            if ($purchase->status === PurchaseStatus::CANCELLED) {
                throw new Exception("La compra ya está anulada.");
            }

            if ($purchase->paid_amount > 0) {
                throw new Exception("No se puede anular una compra que tiene pagos registrados. Debe revertir los pagos primero.");
            }

            $moduleManager = app(ModuleManager::class);
            $inventoryActive = $moduleManager->isActive($company, 'inventory');
            
            if ($purchase->status !== PurchaseStatus::DRAFT) {
                $purchase->load('items.product');
                foreach ($purchase->items as $item) {
                    if ($inventoryActive && $item->product && $item->product->track_inventory) {
                        app(\App\Services\Inventory\InventoryService::class)->registerExit(
                            $purchase->warehouse,
                            $item->product,
                            $item->quantity,
                            Purchase::class,
                            $purchase->id,
                            "Anulación de compra {$purchase->number}"
                        );
                    }
                }
            }

            if ($purchase->payable) {
                $purchase->payable->status = PayableStatus::CANCELLED;
                $purchase->payable->balance = 0;
                $purchase->payable->save();
            }

            $purchase->status = PurchaseStatus::CANCELLED;
            $purchase->cancelled_at = now();
            $purchase->balance = 0;
            $purchase->save();
        });
    }
}
