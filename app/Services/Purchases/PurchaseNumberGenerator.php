<?php

namespace App\Services\Purchases;

use App\Models\Purchase;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class PurchaseNumberGenerator
{
    /**
     * Generates a unique purchase number for the given company.
     * Must be called within a database transaction.
     */
    public function generate(int $companyId, string $prefix = 'COM-'): string
    {
        // Lock the company record to prevent concurrent number generation
        Company::where('id', $companyId)->lockForUpdate()->first();

        // Find the highest existing number with this prefix
        $lastPurchase = Purchase::where('company_id', $companyId)
            ->whereNotNull('number')
            ->where('number', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(number) DESC')
            ->orderBy('number', 'desc')
            ->first();

        if (!$lastPurchase) {
            $nextSequence = 1;
        } else {
            $lastNumberStr = str_replace($prefix, '', $lastPurchase->number);
            $nextSequence = intval($lastNumberStr) + 1;
        }

        return $prefix . str_pad($nextSequence, 6, '0', STR_PAD_LEFT);
    }
}
