<?php
namespace App\Livewire\Catalog;

use \App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $type = ''; // product or service

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingType()
    {
        $this->resetPage();
    }

    public function deactivate($productId)
    {
        // Require permission? Actually, the contract says "deactivate (cambia active a false)"
        $product = Product::findOrFail($productId);
        $product->update(['active' => false]);
    }
    
    public function activate($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['active' => true]);
    }

    public function render()
    {
        $query = Product::with(['category', 'unit'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->type, function ($q) {
                $q->where('type', $this->type);
            });

        // Depending on the route (products vs services), we might want to filter automatically.
        // Actually, the route defines if we are in products or services, but for now we filter via UI.

        return view('livewire.catalog.product-list', [
            'products' => $query->orderBy('name')->paginate(15)
        ])->layout('layouts.app');
    }
}
