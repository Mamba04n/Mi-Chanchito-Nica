<?php
namespace App\Livewire\Suppliers;
use Livewire\Component;

class SupplierForm extends Component
{
    public $supplierId;
    public $type;
    public $name;
    public $legal_name;
    public $identification;
    public $email;
    public $phone;
    public $address;
    public $notes;
    public $payment_terms_days = 0;
    
    public function save() {}
    
    public function render()
    {
        return view('livewire.suppliers.supplier-form')->layout('layouts.app');
    }
}
