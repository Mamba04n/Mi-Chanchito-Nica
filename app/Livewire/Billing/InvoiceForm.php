<?php

namespace App\Livewire\Billing;

use Livewire\Component;

class InvoiceForm extends Component
{
    public $invoiceId = null;
    
    public $customer_id = '';
    public $issue_date = '';
    public $due_date = '';
    public $currency = 'NIO';
    public $sale_type = 'credit';
    public $notes = '';
    public $items = [];
    
    public $subtotal = 0;
    public $tax_total = 0;
    public $discount_total = 0;
    public $total = 0;

    public function mount($id = null)
    {
        if ($id) {
            $this->invoiceId = $id;
            $invoice = \App\Models\Invoice::with('items')->findOrFail($id);
            $this->customer_id = $invoice->customer_id;
            $this->issue_date = $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : '';
            $this->due_date = $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '';
            $this->currency = $invoice->currency ?? 'NIO';
            $this->sale_type = $invoice->sale_type ?? 'credit';
            $this->notes = $invoice->notes;
            
            foreach ($invoice->items as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'warehouse_id' => $item->warehouse_id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount ?? 0,
                    'tax' => $item->tax ?? 0,
                ];
            }
            $this->calculateTotals();
        } else {
            $this->issue_date = date('Y-m-d');
            $this->addItem();
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => '',
            'warehouse_id' => '',
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'discount' => 0,
            'tax' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }
    
    public function updatedItems()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        if (class_exists(\App\Actions\Billing\CalculateInvoiceTotals::class)) {
            $totals = app(\App\Actions\Billing\CalculateInvoiceTotals::class)->execute($this->items);
            $this->subtotal = $totals['subtotal'] ?? 0;
            $this->tax_total = $totals['tax_total'] ?? 0;
            $this->discount_total = $totals['discount_total'] ?? 0;
            $this->total = $totals['total'] ?? 0;
        } else {
            $sub = 0; $tax = 0; $disc = 0;
            foreach ($this->items as $item) {
                $qty = (float)($item['quantity'] ?? 0);
                $price = (float)($item['unit_price'] ?? 0);
                $d = (float)($item['discount'] ?? 0);
                $t = (float)($item['tax'] ?? 0);
                
                $lineSub = $qty * $price;
                $lineDisc = $lineSub * ($d / 100);
                $lineTax = ($lineSub - $lineDisc) * ($t / 100);
                
                $sub += $lineSub;
                $disc += $lineDisc;
                $tax += $lineTax;
            }
            $this->subtotal = $sub;
            $this->discount_total = $disc;
            $this->tax_total = $tax;
            $this->total = $sub - $disc + $tax;
        }
    }

    public function save()
    {
        $this->validate([
            'customer_id' => 'required',
            'issue_date' => 'required|date',
            'sale_type' => 'required',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $data = [
            'customer_id' => $this->customer_id,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'currency' => $this->currency,
            'sale_type' => $this->sale_type,
            'notes' => $this->notes,
            'items' => $this->items,
        ];

        try {
            if ($this->invoiceId) {
                $invoice = app(\App\Actions\Billing\UpdateInvoiceDraft::class)->execute($this->invoiceId, $data);
            } else {
                $invoice = app(\App\Actions\Billing\CreateInvoiceDraft::class)->execute($data);
            }
            session()->flash('success', 'Factura guardada correctamente.');
            return redirect()->to('/billing/invoices/' . ($invoice->id ?? $this->invoiceId));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.billing.invoice-form')->layout('layouts.app');
    }
}
