<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="font-gliker text-28pt text-brand-navy-900 leading-tight">
            {{ $purchaseId ? 'Editar Compra' : 'Nueva Compra' }}
        </h1>
    </div>

    @if (session()->has('error'))
        <div class="mb-4 bg-brand-coral-500 bg-opacity-10 border border-brand-coral-500 text-brand-coral-500 px-4 py-3 rounded-xl font-sans text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold font-sans text-brand-navy-900 mb-1">Proveedor *</label>
                <select wire:model="supplier_id" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 py-3 px-4 font-sans text-sm">
                    <option value="">Seleccione un proveedor</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id') <span class="text-brand-coral-500 text-xs font-sans mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold font-sans text-brand-navy-900 mb-1">Almacén Destino *</label>
                <select wire:model="warehouse_id" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 py-3 px-4 font-sans text-sm">
                    <option value="">Seleccione un almacén</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                @error('warehouse_id') <span class="text-brand-coral-500 text-xs font-sans mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold font-sans text-brand-navy-900 mb-1">Fecha de Compra *</label>
                <input wire:model="purchase_date" type="date" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 py-3 px-4 font-sans text-sm">
                @error('purchase_date') <span class="text-brand-coral-500 text-xs font-sans mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold font-sans text-brand-navy-900 mb-1">Fecha de Vencimiento (Crédito)</label>
                <input wire:model="due_date" type="date" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 py-3 px-4 font-sans text-sm">
                @error('due_date') <span class="text-brand-coral-500 text-xs font-sans mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-sans text-lg font-bold text-brand-navy-900 mb-4 border-b pb-2">Detalles de Compra</h3>
            
            @foreach($items as $index => $item)
                <div class="grid grid-cols-12 gap-4 items-end mb-4 border-b border-gray-100 pb-4">
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-xs font-semibold font-sans text-neutral-charcoal mb-1">Producto/Descripción *</label>
                        <input wire:model="items.{{ $index }}.description" type="text" class="w-full rounded-xl border-gray-200 shadow-sm py-2 px-3 font-sans text-sm">
                        @error('items.'.$index.'.description') <span class="text-brand-coral-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-6 md:col-span-2">
                        <label class="block text-xs font-semibold font-sans text-neutral-charcoal mb-1">Cantidad *</label>
                        <input wire:model="items.{{ $index }}.quantity" type="number" step="0.01" class="w-full rounded-xl border-gray-200 shadow-sm py-2 px-3 font-sans text-sm text-right">
                        @error('items.'.$index.'.quantity') <span class="text-brand-coral-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-6 md:col-span-2">
                        <label class="block text-xs font-semibold font-sans text-neutral-charcoal mb-1">Costo Unit. *</label>
                        <input wire:model="items.{{ $index }}.unit_cost" type="number" step="0.01" class="w-full rounded-xl border-gray-200 shadow-sm py-2 px-3 font-sans text-sm text-right">
                        @error('items.'.$index.'.unit_cost') <span class="text-brand-coral-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-10 md:col-span-3 pt-4 md:pt-0 text-right">
                        <span class="block text-xs font-semibold text-neutral-charcoal mb-1">Subtotal</span>
                        <span class="font-sans text-sm font-bold text-brand-navy-900">
                            C$ {{ number_format((floatval($item['quantity'] ?? 0) * floatval($item['unit_cost'] ?? 0)), 2) }}
                        </span>
                    </div>
                    
                    <div class="col-span-2 md:col-span-1 text-right">
                        @if(count($items) > 1)
                            <button type="button" wire:click="removeItem({{ $index }})" class="text-brand-coral-500 hover:text-red-700 p-2" title="Eliminar línea">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
            @error('items') <span class="text-brand-coral-500 text-xs font-sans mb-4 block">{{ $message }}</span> @enderror
            
            <button type="button" wire:click="addItem" class="text-brand-green-700 font-sans font-semibold text-sm hover:text-brand-green-500 flex items-center mt-2">
                <span class="text-xl mr-1">+</span> Agregar línea
            </button>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold font-sans text-brand-navy-900 mb-1">Notas</label>
            <textarea wire:model="notes" rows="3" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 py-3 px-4 font-sans text-sm"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="/purchases" class="px-5 py-2 border border-gray-300 rounded-xl font-sans font-semibold text-neutral-charcoal hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button wire:click="save" class="px-5 py-2 bg-brand-green-700 rounded-xl font-sans font-semibold text-white hover:bg-brand-green-500 transition shadow-sm">
                Guardar Borrador
            </button>
        </div>
    </div>
</div>
