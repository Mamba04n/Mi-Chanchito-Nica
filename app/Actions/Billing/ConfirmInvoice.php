<?php

namespace App\Actions\Billing;

use App\Models\Invoice;
use App\Models\AccountReceivable;
use App\Services\Billing\InvoiceNumberGenerator;
use App\Services\Billing\CalculateInvoiceTotals;
use App\Services\Inventory\InventoryService;
use App\Enums\InvoiceStatus;
use App\Enums\ReceivableStatus;
use App\Context\CompanyContext;
use App\Services\ModuleManager;
use Illuminate\Support\Facades\DB;
use Exception;

class ConfirmInvoice
{
    protected InvoiceNumberGenerator $numberGenerator;
    protected CalculateInvoiceTotals $totalsCalculator;
    protected InventoryService $inventoryService;

    public function __construct(
        InvoiceNumberGenerator $numberGenerator,
        CalculateInvoiceTotals $totalsCalculator,
        InventoryService $inventoryService
    ) {
        $this->numberGenerator = $numberGenerator;
        $this->totalsCalculator = $totalsCalculator;
        $this->inventoryService = $inventoryService;
    }

    public function execute(Invoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $company = app(CompanyContext::class)->getCompany();
            $companyId = $company->id;
            $moduleManager = app(\App\Services\ModuleManager::class);

            if ($invoice->company_id !== $companyId) {
                throw new Exception("La factura no pertenece a la empresa activa.");
            }

            if (!$moduleManager->isActive($company, 'billing')) {
                throw new Exception("El módulo de facturación no está activo.");
            }

            if ($invoice->status !== InvoiceStatus::DRAFT) {
                throw new Exception("Solo se pueden confirmar facturas en borrador.");
            }

            if ($invoice->items()->count() === 0) {
                throw new Exception("La factura debe tener al menos una línea.");
            }

            // Recalculate totals
            $this->totalsCalculator->execute($invoice);

            // Reload to get updated totals
            $invoice->refresh();

            // Assign Number
            if (!$invoice->number) {
                $invoice->number = $this->numberGenerator->generate($companyId);
            }

            // Inventory Integration
            $inventoryActive = $moduleManager->isActive($company, 'inventory');

            foreach ($invoice->items as $item) {
                if ($item->product_id) {
                    $product = $item->product;
                    if ($product && $product->track_inventory) {
                        if (!$inventoryActive) {
                            throw new Exception("El producto {$product->name} requiere inventario, pero el módulo no está activo.");
                        }

                        if (!$item->warehouse_id) {
                            throw new Exception("Se requiere almacén para el producto {$product->name}.");
                        }

                        $warehouse = $item->warehouse;
                        // Deduct stock (will throw Exception if insufficient)
                        $this->inventoryService->registerExit(
                            $warehouse,
                            $product,
                            $item->quantity,
                            Invoice::class,
                            $invoice->id,
                            "Venta según factura {$invoice->number}"
                        );
                    }
                }
            }

            // Generate CxC if credit
            if ($invoice->sale_type->value === 'credit') {
                if ($invoice->total <= 0) {
                    throw new Exception("No se puede crear un crédito por un total de 0.");
                }

                AccountReceivable::create([
                    'company_id' => $companyId,
                    'customer_id' => $invoice->customer_id,
                    'invoice_id' => $invoice->id,
                    'original_amount' => $invoice->total,
                    'paid_amount' => 0,
                    'balance' => $invoice->total,
                    'issued_at' => $invoice->issue_date,
                    'due_date' => $invoice->due_date ?? now()->addDays(30), // Defaulting if null
                    'status' => ReceivableStatus::PENDING,
                ]);
            } else {
                // If it's a cash sale, we consider it fully paid
                $invoice->paid_amount = $invoice->total;
                $invoice->balance = 0;
            }

            // Change status
            $invoice->status = $invoice->sale_type->value === 'credit' ? InvoiceStatus::CONFIRMED : InvoiceStatus::PAID;
            $invoice->confirmed_at = now();
            $invoice->save();

        });
    }
}
