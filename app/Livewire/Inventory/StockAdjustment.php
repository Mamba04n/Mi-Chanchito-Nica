<?php

namespace App\Livewire\Inventory;

use App\Models\Warehouse;
use App\Models\Product;
use App\Models\InventoryStock;
use App\Services\Inventory\InventoryService;
use App\Context\CompanyContext;
use Livewire\Component;

class StockAdjustment extends Component
{
    public $warehouse_id = '';
    public $product_id = '';
    public $real_quantity = '';
    public $reason = '';
    public $notes = '';

    public $currentStock = 0;
    public $difference = 0;

    public function updatedWarehouseId()
    {
        $this->calculateDifference();
    }

    public function updatedProductId()
    {
        $this->calculateDifference();
    }

    public function updatedRealQuantity()
    {
        $this->calculateDifference();
    }

    public function calculateDifference()
    {
        if ($this->warehouse_id && $this->product_id) {
            $stock = InventoryStock::where('warehouse_id', $this->warehouse_id)
                ->where('product_id', $this->product_id)
                ->first();
            $this->currentStock = $stock ? $stock->quantity : 0;
            
            if ($this->real_quantity !== '') {
                $this->difference = (float)$this->real_quantity - (float)$this->currentStock;
            } else {
                $this->difference = 0;
            }
        }
    }

    public function submit(InventoryService $service)
    {
        $this->validate([
            'warehouse_id' => 'required',
            'product_id' => 'required',
            'real_quantity' => 'required|numeric|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $warehouse = Warehouse::where('company_id', app(CompanyContext::class)->getCompanyId())->findOrFail($this->warehouse_id);
        $product = Product::where('company_id', app(CompanyContext::class)->getCompanyId())->findOrFail($this->product_id);

        try {
            $service->adjustStock($warehouse, $product, (float)$this->real_quantity, $this->reason, $this->notes);
            session()->flash('success', 'Stock ajustado correctamente.');
            return $this->redirectRoute('inventory.movements');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $companyId = app(CompanyContext::class)->getCompanyId();
        $warehouses = Warehouse::where('company_id', $companyId)->where('active', true)->get();
        $products = Product::where('company_id', $companyId)->where('track_inventory', true)->where('active', true)->get();

        return view('livewire.inventory.adjustments.form', [
            'warehouses' => $warehouses,
            'products' => $products
        ])->layout('layouts.app');
    }
}
