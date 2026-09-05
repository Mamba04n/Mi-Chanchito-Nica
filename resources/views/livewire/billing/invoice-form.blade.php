<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8 font-sans">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#172238] font-gliker">{{ $invoiceId ? 'Editar Borrador' : 'Nueva Factura' }}</h1>
        <p class="text-sm text-gray-500">Completa los detalles de la venta.</p>
    </div>

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 p-4 rounded-xl border border-red-200 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <!-- Encabezado Factura -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-[#172238] mb-4">Datos Generales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-[#172238] mb-1">Cliente</label>
                    <input type="text" wire:model="customer_id" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-[#1D6B46] focus:ring-[#1D6B46]" placeholder="ID del Cliente">
                    @error('customer_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#172238] mb-1">Tipo de Venta</label>
                    <select wire:model="sale_type" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-[#1D6B46] focus:ring-[#1D6B46]">
                        <option value="cash">Contado</option>
                        <option value="credit">Crédito</option>
                    </select>
                    @error('sale_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#172238] mb-1">Fecha de Emisión</label>
                    <input type="date" wire:model="issue_date" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-[#1D6B46] focus:ring-[#1D6B46]">
                    @error('issue_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#172238] mb-1">Fecha de Vencimiento (Opcional)</label>
                    <input type="date" wire:model="due_date" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-[#1D6B46] focus:ring-[#1D6B46]">
                    @error('due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Líneas de Factura -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-[#172238]">Artículos</h2>
                <button type="button" wire:click="addItem" class="text-sm font-bold text-[#1D6B46] hover:text-[#155436]">+ Agregar Artículo</button>
            </div>

            <div class="space-y-4">
                @foreach($items as $index => $item)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex flex-col md:flex-row gap-4 relative">
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-[#172238] mb-1">Producto ID</label>
                            <input type="text" wire:model="items.{{ $index }}.product_id" class="block w-full rounded-lg border-gray-200 py-2 text-sm focus:border-[#1D6B46] focus:ring-[#1D6B46]">
                            @error('items.'.$index.'.product_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full md:w-24">
                            <label class="block text-xs font-semibold text-[#172238] mb-1">Cant.</label>
                            <input type="number" step="0.01" wire:model.blur="items.{{ $index }}.quantity" class="block w-full rounded-lg border-gray-200 py-2 text-sm focus:border-[#1D6B46] focus:ring-[#1D6B46]">
                            @error('items.'.$index.'.quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full md:w-32">
                            <label class="block text-xs font-semibold text-[#172238] mb-1">Precio Unit.</label>
                            <input type="number" step="0.01" wire:model.blur="items.{{ $index }}.unit_price" class="block w-full rounded-lg border-gray-200 py-2 text-sm focus:border-[#1D6B46] focus:ring-[#1D6B46]">
                        </div>
                        <div class="flex items-end justify-center pb-2">
                            @if(count($items) > 1)
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700 text-sm font-bold px-2 py-1">
                                    Eliminar
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
                @error('items') <div class="text-red-500 text-sm font-bold mt-2">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Totales y Notas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-[#172238] mb-4">Notas Adicionales</h2>
                <textarea wire:model="notes" rows="4" class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:border-[#1D6B46] focus:ring-[#1D6B46]" placeholder="Comentarios visibles en la factura..."></textarea>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-semibold text-[#172238]">{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Descuento</span>
                        <span class="font-semibold text-[#172238]">-{{ number_format($discount_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Impuestos</span>
                        <span class="font-semibold text-[#172238]">{{ number_format($tax_total, 2) }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200 flex justify-between text-lg font-bold text-[#172238]">
                        <span>Total ({{ $currency }})</span>
                        <span>{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex justify-end gap-3">
            <a href="/billing/invoices" class="px-4 py-2 border border-gray-300 rounded-xl shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1D6B46]">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#1D6B46] hover:bg-[#155436] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1D6B46]">
                Guardar Borrador
            </button>
        </div>
    </form>
</div>
