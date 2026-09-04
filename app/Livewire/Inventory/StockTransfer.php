<?php

namespace App\Livewire\Inventory;

use App\Models\Warehouse;
use App\Models\Product;
use App\Models\InventoryStock;
use App\Services\Inventory\InventoryService;
use App\Context\CompanyContext;
use Livewire\Component;

class StockTransfer extends Component
{
    public $source_warehouse_id = '';
    public $destination_warehouse_id = '';
    public $product_id = '';
    public $quantity = '';
    public $notes = '';
    public $available_quantity = 0;

    public function updatedSourceWarehouseId()
    {
        $this->checkAvailable();
    }

    public function updatedProductId()
    {
        $this->checkAvailable();
    }

    public function checkAvailable()
    {
        if ($this->source_warehouse_id && $this->product_id) {
            $stock = InventoryStock::where('warehouse_id', $this->source_warehouse_id)
                ->where('product_id', $this->product_id)
                ->first();
            $this->available_quantity = $stock ? $stock->available_quantity : 0;
        } else {
            $this->available_quantity = 0;
        }
    }

    public function submit(InventoryService $service)
    {
        $this->validate([
            'source_warehouse_id' => 'required|different:destination_warehouse_id',
            'destination_warehouse_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:0.01|max:'.$this->available_quantity,
            'notes' => 'nullable|string',
        ]);

        $companyId = app(CompanyContext::class)->getCompanyId();
        $source = Warehouse::where('company_id', $companyId)->findOrFail($this->source_warehouse_id);
        $destination = Warehouse::where('company_id', $companyId)->findOrFail($this->destination_warehouse_id);
        $product = Product::where('company_id', $companyId)->findOrFail($this->product_id);

        try {
            $service->transferStock($source, $destination, $product, (float)$this->quantity, $this->notes);
            session()->flash('success', 'Transferencia realizada correctamente.');
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

        return view('livewire.inventory.transfers.form', [
            'warehouses' => $warehouses,
            'products' => $products
        ])->layout('layouts.app');
    }
}
