<?php
namespace App\Livewire\Catalog;
use Livewire\Component;

class UnitList extends Component
{
    public function render()
    {
        return view('livewire.catalog.unit-list')->layout('layouts.app');
    }
}
