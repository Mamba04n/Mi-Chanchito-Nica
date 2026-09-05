<?php

namespace App\Services\Purchases;

use App\Models\Purchase;

class CalculatePurchaseTotals
{
    /**
     * Calculates and updates the totals for a purchase based on its items.
     *
     * @param Purchase $purchase
     * @return void
     */
    public function execute(Purchase $purchase): void
    {
        $subtotal = 0;
        $discountTotal = 0;
        $taxTotal = 0;
        $total = 0;

        foreach ($purchase->items as $item) {
            $itemSubtotal = $item->quantity * $item->unit_cost;
            $itemDiscount = $item->discount ?? 0;
            $itemTax = $item->tax ?? 0;
            $itemTotal = $itemSubtotal - $itemDiscount + $itemTax;

            $item->subtotal = $itemSubtotal;
            $item->total = $itemTotal;
            // Solo actualiza en memoria, quien llame al servicio debe hacer save() de los items si corresponde.
            
            $subtotal += $itemSubtotal;
            $discountTotal += $itemDiscount;
            $taxTotal += $itemTax;
            $total += $itemTotal;
        }

        $purchase->subtotal = $subtotal;
        $purchase->discount_total = $discountTotal;
        $purchase->tax_total = $taxTotal;
        $purchase->total = $total;
        $purchase->balance = $total - $purchase->paid_amount;
    }
}
