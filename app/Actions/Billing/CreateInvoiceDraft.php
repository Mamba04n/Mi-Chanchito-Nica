<?php

namespace App\Actions\Billing;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Enums\InvoiceStatus;
use App\Enums\SaleType;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\DB;

class CreateInvoiceDraft
{
    public function execute(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $companyId = app(CompanyContext::class)->getCompanyId();

            $invoice = Invoice::create([
                'company_id' => $companyId,
                'customer_id' => $data['customer_id'],
                'issue_date' => $data['issue_date'],
                'due_date' => $data['due_date'] ?? null,
                'currency' => $data['currency'] ?? 'NIO',
                'sale_type' => SaleType::from($data['sale_type']),
                'status' => InvoiceStatus::DRAFT,
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $invoice->items()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'warehouse_id' => $item['warehouse_id'] ?? null,
                        'description' => $item['description'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount' => $item['discount'] ?? 0,
                        'tax' => $item['tax'] ?? 0,
                        'subtotal' => 0,
                        'total' => 0,
                    ]);
                }
                
                app(\App\Services\Billing\CalculateInvoiceTotals::class)->execute($invoice);
            }

            return $invoice;
        });
    }
}
