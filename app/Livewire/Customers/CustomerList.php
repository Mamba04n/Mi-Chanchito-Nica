<?php
namespace App\Livewire\Customers;

use \App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deactivate($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['active' => false]);
    }
    
    public function activate($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['active' => true]);
    }

    public function render()
    {
        $query = Customer::query()
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('identification', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            });

        return view('livewire.customers.customer-list', [
            'customers' => $query->orderBy('name')->paginate(15)
        ])->layout('layouts.app');
    }
}
