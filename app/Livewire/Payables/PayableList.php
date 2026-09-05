<?php
namespace App\Livewire\Payables;

use \App\Models\AccountPayable;
use Livewire\Component;
use Livewire\WithPagination;

class PayableList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = class_exists('\App\Models\AccountPayable') ? \App\Models\AccountPayable::query() : collect();
        // Optional search logic can be added here
        
        return view('livewire.' . strtolower('Payables') . '.' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', 'PayableList')), [
            'payables' => class_exists('\App\Models\AccountPayable') ? $query->paginate(15) : collect()
        ])->layout('layouts.app');
    }
}
