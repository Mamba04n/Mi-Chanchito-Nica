<?php
namespace App\Livewire\Suppliers;

use \App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = class_exists('\App\Models\Supplier') ? \App\Models\Supplier::query() : collect();
        // Optional search logic can be added here
        
        return view('livewire.' . strtolower('Suppliers') . '.' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', 'SupplierList')), [
            'suppliers' => class_exists('\App\Models\Supplier') ? $query->paginate(15) : collect()
        ])->layout('layouts.app');
    }
}
