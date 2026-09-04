<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryMovement;
use App\Models\Warehouse;
use App\Models\Product;
use App\Context\CompanyContext;
use Livewire\Component;
use Livewire\WithPagination;

class Kardex extends Component
{
    use WithPagination;

    public $product_id = null;
    public $warehouse_id = null;
    public $dateFrom = '';
    public $dateTo = '';

    public function render()
    {
        $companyId = app(CompanyContext::class)->getCompanyId();

        $query = InventoryMovement::with(['product', 'warehouse', 'user'])
            ->where('company_id', $companyId)
            ->orderBy('occurred_at', 'asc');

        if ($this->product_id) {
            $query->where('product_id', $this->product_id);
        } else {
            // Force empty query if no product is selected to avoid huge data dump
            $query->whereRaw('1 = 0'); 
        }

        if ($this->warehouse_id) {
            $query->where('warehouse_id', $this->warehouse_id);
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('occurred_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('occurred_at', '<=', $this->dateTo);
        }

        $records = $query->paginate(20);

        $warehouses = Warehouse::where('company_id', $companyId)->get();
        $products = Product::where('company_id', $companyId)->where('track_inventory', true)->get();

        return view('livewire.inventory.kardex.index', [
            'records' => $records,
            'warehouses' => $warehouses,
            'products' => $products
        ])->layout('layouts.app');
    }
}
