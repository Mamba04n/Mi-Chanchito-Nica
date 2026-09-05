<?php
namespace App\Livewire\Purchases;

use \App\Models\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = class_exists('\App\Models\Purchase') ? \App\Models\Purchase::query() : collect();
        // Optional search logic can be added here
        
        return view('livewire.' . strtolower('Purchases') . '.' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', 'PurchaseList')), [
            'purchases' => class_exists('\App\Models\Purchase') ? $query->paginate(15) : collect()
        ])->layout('layouts.app');
    }
}
