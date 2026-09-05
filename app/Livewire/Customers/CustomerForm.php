<?php
namespace App\Livewire\Customers;
use Livewire\Component;

class CustomerForm extends Component
{
    public $customerId;
    public $type;
    public $name;
    public $legal_name;
    public $identification;
    public $email;
    public $phone;
    public $address;
    public $credit_limit;
    public $credit_days;
    public $notes;
    
    public function save() {}
    
    public function render()
    {
        return view('livewire.customers.customer-form')->layout('layouts.app');
    }
}
