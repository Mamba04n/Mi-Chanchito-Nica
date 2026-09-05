<?php
namespace App\Livewire\Catalog;
use Livewire\Component;

class ProductForm extends Component
{
    public $productId;
    public $category_id;
    public $unit_id;
    public $sku;
    public $name;
    public $description;
    public $type;
    public $sale_price;
    public $cost;
    public $track_inventory;
    
    public $categories = [];
    public $units = [];
    
    public function save() {}
    
    public function render()
    {
        return view('livewire.catalog.product-form')->layout('layouts.app');
    }
}
