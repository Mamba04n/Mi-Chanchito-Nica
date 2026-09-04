<?php

namespace App\Services\Inventory;

use App\Models\Product;
use App\Models\InventoryStock;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\DB;

class InventoryDashboardService
{
    public function getIndicators(): array
    {
        $companyId = app(CompanyContext::class)->getCompanyId();
        
        $totalProducts = Product::where('company_id', $companyId)
            ->where('track_inventory', true)
            ->where('active', true)
            ->count();

        // Stock agg
        $stocks = InventoryStock::where('company_id', $companyId)
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('MAX(minimum_stock) as min_stock'))
            ->groupBy('product_id')
            ->get();

        $withStock = 0;
        $lowStock = 0;
        $outOfStock = 0;

        foreach ($stocks as $stock) {
            if ($stock->total_quantity <= 0) {
                $outOfStock++;
            } else {
                $withStock++;
                if ($stock->min_stock !== null && $stock->total_quantity <= $stock->min_stock) {
                    $lowStock++;
                }
            }
        }

        // Approx Value: sum of (product cost * stock quantity)
        // Since we aggregate by product:
        $value = DB::table('inventory_stocks')
            ->join('products', 'inventory_stocks.product_id', '=', 'products.id')
            ->where('inventory_stocks.company_id', $companyId)
            ->sum(DB::raw('inventory_stocks.quantity * products.cost'));

        return [
            'total_inventoriable_products' => $totalProducts,
            'products_with_stock' => $withStock,
            'products_low_stock' => $lowStock,
            'products_out_of_stock' => $outOfStock,
            'approximate_inventory_value' => $value,
        ];
    }
}
