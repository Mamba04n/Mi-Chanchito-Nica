<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryStock;
use App\Models\Warehouse;
use App\Services\Inventory\InventoryDashboardService;
use App\Context\CompanyContext;
use Livewire\Component;

class ProductStockList extends Component
{
    public $filterWarehouseId = null;
    public $searchProduct = '';

    public function render()
    {
        $companyId = app(CompanyContext::class)->getCompanyId();

        $stocksQuery = InventoryStock::with(['product', 'warehouse'])
            ->where('company_id', $companyId)
            ->whereHas('product', function ($q) {
                if (!empty($this->searchProduct)) {
                    $q->where('name', 'like', '%' . $this->searchProduct . '%')
                      ->orWhere('sku', 'like', '%' . $this->searchProduct . '%');
                }
                $q->where('track_inventory', true)
                  ->where('type', '!=', 'service');
            });

        if ($this->filterWarehouseId) {
            $stocksQuery->where('warehouse_id', $this->filterWarehouseId);
        }

        $stocks = $stocksQuery->get();

        $warehouses = Warehouse::where('company_id', $companyId)->where('active', true)->get();
        
        $indicators = app(InventoryDashboardService::class)->getIndicators();

        return view('livewire.inventory.stock.index', [
            'stocks' => $stocks,
            'warehouses' => $warehouses,
            'indicators' => $indicators,
        ])->layout('layouts.app');
    }
}
