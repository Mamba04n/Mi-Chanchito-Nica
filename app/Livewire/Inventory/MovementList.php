<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryMovement;
use App\Models\Warehouse;
use App\Context\CompanyContext;
use Livewire\Component;
use Livewire\WithPagination;

class MovementList extends Component
{
    use WithPagination;

    public $filterWarehouseId = null;
    public $filterType = null;
    public $dateFrom = '';
    public $dateTo = '';

    protected $queryString = ['filterWarehouseId', 'filterType', 'dateFrom', 'dateTo'];

    public function updating($name, $value)
    {
        $this->resetPage();
    }

    public function render()
    {
        $companyId = app(CompanyContext::class)->getCompanyId();

        $query = InventoryMovement::with(['product', 'warehouse', 'user'])
            ->where('company_id', $companyId)
            ->orderBy('occurred_at', 'desc');

        if ($this->filterWarehouseId) {
            $query->where('warehouse_id', $this->filterWarehouseId);
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('occurred_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('occurred_at', '<=', $this->dateTo);
        }

        $movements = $query->paginate(15);
        $warehouses = Warehouse::where('company_id', $companyId)->get();

        return view('livewire.inventory.movements.index', [
            'movements' => $movements,
            'warehouses' => $warehouses
        ])->layout('layouts.app');
    }
}
