<?php
namespace App\Livewire\Treasury;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.treasury.dashboard')->layout('layouts.app');
    }
}
