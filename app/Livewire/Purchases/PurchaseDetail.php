<?php

namespace App\Livewire\Purchases;

use Livewire\Component;
use App\Models\Purchase;
use App\Actions\Purchases\ConfirmPurchase;
use App\Actions\Purchases\CancelPurchase;
use Illuminate\Support\Facades\Log;

class PurchaseDetail extends Component
{
    public $purchaseId;

    public function mount($id)
    {
        $this->purchaseId = $id;
    }

    public function confirmPurchase()
    {
        $purchase = Purchase::findOrFail($this->purchaseId);
        
        try {
            app(ConfirmPurchase::class)->execute($purchase);
            session()->flash('success', 'Compra confirmada exitosamente. Se ha actualizado el inventario.');
            return redirect()->to('/purchases/' . $this->purchaseId);
        } catch (\Exception $e) {
            Log::error('Error confirming purchase: ' . $e->getMessage());
            session()->flash('error', 'Error al confirmar: ' . $e->getMessage());
        }
    }

    public function cancelPurchase()
    {
        $purchase = Purchase::findOrFail($this->purchaseId);
        
        try {
            app(CancelPurchase::class)->execute($purchase);
            session()->flash('success', 'Compra anulada.');
            return redirect()->to('/purchases/' . $this->purchaseId);
        } catch (\Exception $e) {
            Log::error('Error cancelling purchase: ' . $e->getMessage());
            session()->flash('error', 'Error al anular: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $purchase = Purchase::with(['supplier', 'warehouse', 'items'])->findOrFail($this->purchaseId);
        
        return view('livewire.purchases.purchase-detail', [
            'purchase' => $purchase
        ])->layout('layouts.app');
    }
}
