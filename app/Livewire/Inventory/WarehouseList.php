<?php

namespace App\Livewire\Inventory;

use App\Models\Warehouse;
use App\Context\CompanyContext;
use Livewire\Component;

class WarehouseList extends Component
{
    public $code = '';
    public $name = '';
    public $description = '';
    public $address = '';
    public $active = true;
    public $is_default = false;

    public $editId = null;
    public $showModal = false;

    public function rules()
    {
        $companyId = app(CompanyContext::class)->getCompanyId();
        return [
            'code' => 'required|string|max:255|unique:warehouses,code,' . $this->editId . ',id,company_id,' . $companyId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function create()
    {
        $this->reset(['code', 'name', 'description', 'address', 'active', 'is_default', 'editId']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $warehouse = Warehouse::where('company_id', app(CompanyContext::class)->getCompanyId())->findOrFail($id);
        
        $this->editId = $warehouse->id;
        $this->code = $warehouse->code;
        $this->name = $warehouse->name;
        $this->description = $warehouse->description;
        $this->address = $warehouse->address;
        $this->active = $warehouse->active;
        $this->is_default = $warehouse->is_default;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $companyId = app(CompanyContext::class)->getCompanyId();

        if ($this->is_default) {
            Warehouse::where('company_id', $companyId)->update(['is_default' => false]);
        }

        Warehouse::updateOrCreate(
            ['id' => $this->editId, 'company_id' => $companyId],
            [
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'address' => $this->address,
                'active' => $this->active,
                'is_default' => $this->is_default,
            ]
        );

        $this->showModal = false;
        session()->flash('success', 'Almacén guardado correctamente.');
    }

    public function deactivate(int $warehouseId)
    {
        $warehouse = Warehouse::where('company_id', app(CompanyContext::class)->getCompanyId())->findOrFail($warehouseId);
        $warehouse->update(['active' => false]);
        session()->flash('success', 'Almacén desactivado.');
    }

    public function render()
    {
        $warehouses = Warehouse::where('company_id', app(CompanyContext::class)->getCompanyId())->get();
        return view('livewire.inventory.warehouses.index', [
            'warehouses' => $warehouses
        ])->layout('layouts.app');
    }
}
