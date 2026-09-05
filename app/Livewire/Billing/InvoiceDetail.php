<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceDetail extends Component
{
    use AuthorizesRequests;

    public $invoiceId;
    public $invoice;

    public function mount($id)
    {
        $this->invoiceId = $id;
        $this->loadInvoice();
    }
    
    public function loadInvoice()
    {
        $this->invoice = \App\Models\Invoice::with(['items', 'customer'])->findOrFail($this->invoiceId);
        if (auth()->check()) {
            $this->authorize('billing.view', $this->invoice);
        }
    }

    public function confirmInvoice()
    {
        if (auth()->check()) {
            $this->authorize('billing.confirm', $this->invoice);
        }
        
        try {
            app(\App\Actions\Billing\ConfirmInvoice::class)->execute($this->invoice);
            session()->flash('success', 'Factura confirmada exitosamente.');
            $this->loadInvoice();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function cancelInvoice()
    {
        if (auth()->check()) {
            $this->authorize('billing.cancel', $this->invoice);
        }
        
        try {
            app(\App\Actions\Billing\CancelInvoice::class)->execute($this->invoice);
            session()->flash('success', 'Factura cancelada exitosamente.');
            $this->loadInvoice();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.billing.invoice-detail')->layout('layouts.app');
    }
}
