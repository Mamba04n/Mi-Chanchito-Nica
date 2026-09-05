<?php

namespace App\Services\Billing;

use App\Models\Invoice;

class CalculateInvoiceTotals
{
    /**
     * Recalculates all totals for the invoice based on its items.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function execute(Invoice $invoice): void
    {
        $subtotal = 0;
        $discountTotal = 0;
        $taxTotal = 0;

        foreach ($invoice->items as $item) {
            // Recalculate item totals purely based on quantities and prices
            $itemSubtotal = $item->quantity * $item->unit_price;
            $item->subtotal = $itemSubtotal;
            
            // Assume discount and tax are absolute values on the item for the MVP,
            // or we could calculate them based on percentages if they were percentages.
            // The prompt says: "Backend debe calcular: line subtotal, discount, tax, invoice subtotal, invoice total, paid_amount, balance. Nunca confiar en subtotal total balance enviados por Blade."
            // This means we just aggregate them or apply rules.
            // For now, if the item has explicit $item->discount and $item->tax stored, we use them.
            // But we must enforce: total = subtotal - discount + tax
            $item->total = $item->subtotal - $item->discount + $item->tax;
            $item->save();

            $subtotal += $item->subtotal;
            $discountTotal += $item->discount;
            $taxTotal += $item->tax;
        }

        $invoice->subtotal = $subtotal;
        $invoice->discount_total = $discountTotal;
        $invoice->tax_total = $taxTotal;
        $invoice->total = $subtotal - $discountTotal + $taxTotal;
        $invoice->balance = $invoice->total - $invoice->paid_amount;
        $invoice->save();
    }
}
