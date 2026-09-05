<?php
namespace App\Livewire\Treasury;
use Livewire\Component;

class AccountList extends Component
{
    public function render()
    {
        return view('livewire.treasury.account-list')->layout('layouts.app');
    }
}
