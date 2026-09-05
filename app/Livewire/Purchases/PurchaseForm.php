<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Product;
use App\Actions\Purchases\CreatePurchaseDraft;
use Illuminate\Support\Facades\Log;

class PurchaseForm extends Component
{
    public $purchaseId;
    public $supplier_id;
    public $warehouse_id;
    public $purchase_date;
    public $due_date;
    public $notes;
    
    public $items = [];

    protected $rules = [
        'supplier_id' => 'required|exists:suppliers,id',
        'warehouse_id' => 'required|exists:warehouses,id',
        'purchase_date' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'nullable|exists:products,id',
        'items.*.description' => 'required|string',
        'items.*.quantity' => 'required|numeric|min:0.01',
        'items.*.unit_cost' => 'required|numeric|min:0',
    ];

    public function mount($id = null)
    {
        $this->purchase_date = date('Y-m-d');
        if ($id) {
            $this->purchaseId = $id;
            $this->loadPurchase();
        } else {
            $this->addItem();
        }
    }

    public function loadPurchase()
    {
        $purchase = Purchase::with('items')->findOrFail($this->purchaseId);
        $this->supplier_id = $purchase->supplier_id;
        $this->warehouse_id = $purchase->warehouse_id;
        $this->purchase_date = $purchase->purchase_date ? $purchase->purchase_date->format('Y-m-d') : null;
        $this->due_date = $purchase->due_date ? $purchase->due_date->format('Y-m-d') : null;
        $this->notes = $purchase->notes;

        foreach ($purchase->items as $item) {
            $this->items[] = [
                'product_id' => $item->product_id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_cost' => $item->unit_cost,
                'discount' => $item->discount,
                'tax' => $item->tax,
            ];
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => null,
            'description' => '',
            'quantity' => 1,
            'unit_cost' => 0,
            'discount' => 0,
            'tax' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->purchaseId) {
                // Editing existing draft - simple update for now
                $purchase = Purchase::findOrFail($this->purchaseId);
                $purchase->update([
                    'supplier_id' => $this->supplier_id,
                    'warehouse_id' => $this->warehouse_id,
                    'purchase_date' => $this->purchase_date,
                    'due_date' => $this->due_date,
                    'notes' => $this->notes,
                ]);

                // Update items manually
                $purchase->items()->delete();
                foreach ($this->items as $item) {
                    $purchase->items()->create($item);
                }
                
                // Recalculate totals
                app(\App\Services\Purchases\CalculatePurchaseTotals::class)->execute($purchase);
                $purchase->save();
            } else {
                $data = [
                    'supplier_id' => $this->supplier_id,
                    'warehouse_id' => $this->warehouse_id,
                    'purchase_date' => $this->purchase_date,
                    'due_date' => $this->due_date,
                    'notes' => $this->notes,
                ];
                
                $purchase = app(CreatePurchaseDraft::class)->execute($data, $this->items);
            }

            session()->flash('success', 'Compra guardada exitosamente.');
            return redirect()->to('/purchases/' . ($purchase->id ?? $this->purchaseId));
        } catch (\Exception $e) {
            Log::error('Error saving purchase: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al guardar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.purchases.purchase-form', [
            'suppliers' => Supplier::all(),
            'warehouses' => Warehouse::all(),
            'products' => Product::all(),
        ])->layout('layouts.app');
    }
}
