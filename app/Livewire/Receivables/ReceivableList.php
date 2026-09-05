<?php
namespace App\Livewire\Receivables;

use \App\Models\AccountReceivable;
use Livewire\Component;
use Livewire\WithPagination;

class ReceivableList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = class_exists('\App\Models\AccountReceivable') ? \App\Models\AccountReceivable::query() : collect();
        // Optional search logic can be added here
        
        return view('livewire.' . strtolower('Receivables') . '.' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', 'ReceivableList')), [
            'receivables' => class_exists('\App\Models\AccountReceivable') ? $query->paginate(15) : collect()
        ])->layout('layouts.app');
    }
}
