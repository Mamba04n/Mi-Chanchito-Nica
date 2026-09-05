<?php
namespace App\Livewire\Treasury;
use Livewire\Component;

class MovementList extends Component
{
    public function render()
    {
        return view('livewire.treasury.movement-list')->layout('layouts.app');
    }
}
