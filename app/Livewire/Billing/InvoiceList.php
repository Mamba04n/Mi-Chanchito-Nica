<?php
namespace App\Livewire\Billing;

use \App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = class_exists('\App\Models\Invoice') ? \App\Models\Invoice::query() : collect();
        // Optional search logic can be added here
        
        return view('livewire.' . strtolower('Billing') . '.' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', 'InvoiceList')), [
            'invoices' => class_exists('\App\Models\Invoice') ? $query->paginate(15) : collect()
        ])->layout('layouts.app');
    }
}
