<?php

namespace App\Actions\Billing;

use App\Models\Invoice;
use App\Services\Inventory\InventoryService;
use App\Enums\InvoiceStatus;
use App\Enums\ReceivableStatus;
use App\Context\CompanyContext;
use App\Services\ModuleManager;
use Illuminate\Support\Facades\DB;
use Exception;

class CancelInvoice
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function execute(Invoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $company = app(CompanyContext::class)->getCompany();
            $companyId = $company->id;

            if ($invoice->company_id !== $companyId) {
                throw new Exception("La factura no pertenece a la empresa activa.");
            }

            if ($invoice->status === InvoiceStatus::CANCELLED) {
                throw new Exception("La factura ya está anulada.");
            }

            if (in_array($invoice->status, [InvoiceStatus::PAID, InvoiceStatus::PARTIALLY_PAID])) {
                throw new Exception("No se puede anular una factura con pagos aplicados. Revierta los pagos primero.");
            }

            // Reverse Receivable if credit
            if ($invoice->sale_type->value === 'credit' && $invoice->receivable) {
                if ($invoice->receivable->paid_amount > 0) {
                    throw new Exception("No se puede anular la cuenta por cobrar porque tiene abonos.");
                }
                
                $invoice->receivable->status = ReceivableStatus::CANCELLED;
                $invoice->receivable->balance = 0;
                $invoice->receivable->save();
            }

            // Reverse Inventory Movements
            $moduleManager = app(\App\Services\ModuleManager::class);
            $inventoryActive = $moduleManager->isActive($company, 'inventory');
            
            if ($invoice->status !== InvoiceStatus::DRAFT) {
                foreach ($invoice->items as $item) {
                    $product = $item->product;
                    if ($product && $product->track_inventory && $inventoryActive) {
                        $this->inventoryService->registerEntry(
                            $item->warehouse,
                            $product,
                            $item->quantity,
                            Invoice::class,
                            $invoice->id,
                            "Reversión por anulación de factura {$invoice->number}"
                        );
                    }
                }
            }

            $invoice->status = InvoiceStatus::CANCELLED;
            $invoice->cancelled_at = now();
            $invoice->balance = 0;
            $invoice->save();

        });
    }
}
