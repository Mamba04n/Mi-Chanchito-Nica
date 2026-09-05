<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class InvoiceNumberGenerator
{
    /**
     * Generates a unique invoice number for the given company.
     * Must be called within a database transaction.
     */
    public function generate(int $companyId, string $prefix = 'FAC-'): string
    {
        // Lock the company record to prevent concurrent number generation
        Company::where('id', $companyId)->lockForUpdate()->first();

        // Find the highest existing number with this prefix
        $lastInvoice = Invoice::where('company_id', $companyId)
            ->whereNotNull('number')
            ->where('number', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(number) DESC') // In case we cross 999999
            ->orderBy('number', 'desc')
            ->first();

        if (!$lastInvoice) {
            $nextSequence = 1;
        } else {
            $lastNumberStr = str_replace($prefix, '', $lastInvoice->number);
            $nextSequence = intval($lastNumberStr) + 1;
        }

        return $prefix . str_pad($nextSequence, 6, '0', STR_PAD_LEFT);
    }
}
