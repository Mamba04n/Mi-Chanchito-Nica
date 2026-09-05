<?php

namespace App\Actions\Purchases;

use App\Models\Purchase;
use App\Enums\PurchaseStatus;
use App\Context\CompanyContext;
use App\Services\Purchases\CalculatePurchaseTotals;
use Illuminate\Support\Facades\DB;

class CreatePurchaseDraft
{
    public function execute(array $data, array $items): Purchase
    {
        return DB::transaction(function () use ($data, $items) {
            $company = app(CompanyContext::class)->getCompany();

            $purchase = Purchase::create(array_merge($data, [
                'company_id' => $company->id,
                'status' => PurchaseStatus::DRAFT,
                'created_by' => auth()->id(),
                'paid_amount' => 0,
            ]));

            if (!empty($items)) {
                foreach ($items as $item) {
                    $purchase->items()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'description' => $item['description'],
                        'quantity' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'discount' => $item['discount'] ?? 0,
                        'tax' => $item['tax'] ?? 0,
                        'subtotal' => 0,
                        'total' => 0,
                    ]);
                }
                
                $purchase->load('items');
                app(CalculatePurchaseTotals::class)->execute($purchase);
                $purchase->save();
                
                foreach ($purchase->items as $item) {
                    $item->save();
                }
            }

            return $purchase;
        });
    }
}
