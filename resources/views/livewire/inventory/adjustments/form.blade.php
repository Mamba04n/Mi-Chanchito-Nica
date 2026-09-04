<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-brand-navy-900">Ajuste de Inventario</h3>
                    <p class="text-sm text-gray-500 mt-1">Registra la cantidad física contada para ajustar diferencias en el sistema.</p>
                </div>
                
                <form wire:submit.prevent="submit" class="p-6 space-y-6">
                    @if (session()->has('error'))
                        <div class="p-4 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-600">{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                            <select wire:model.live="product_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                <option value="">Seleccione un producto</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Almacén</label>
                            <select wire:model.live="warehouse_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                <option value="">Seleccione un almacén</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200 grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Stock Actual Sistema</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($currentStock, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Nuevo Conteo Físico</p>
                            <div class="mt-1 flex justify-center">
                                <input type="number" step="0.01" wire:model.live="real_quantity" required placeholder="0.00" class="w-24 text-center rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm font-bold">
                            </div>
                            @error('real_quantity') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Diferencia</p>
                            <p class="text-2xl font-bold mt-1 {{ $difference > 0 ? 'text-brand-green-700' : ($difference < 0 ? 'text-brand-coral-500' : 'text-gray-900') }}">
                                {{ $difference > 0 ? '+' : '' }}{{ number_format($difference, 2) }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo</label>
                            <select wire:model="reason" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                <option value="">Seleccione el motivo</option>
                                <option value="Conteo físico">Conteo físico</option>
                                <option value="Daño o merma">Daño o merma</option>
                                <option value="Pérdida">Pérdida</option>
                                <option value="Vencimiento">Vencimiento</option>
                                <option value="Corrección de sistema">Corrección de sistema</option>
                                <option value="Otro">Otro</option>
                            </select>
                            @error('reason') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                            <input type="text" wire:model="notes" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                        <a href="{{ route('inventory.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 mr-3">
                            Cancelar
                        </a>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-brand-green-700 border border-transparent rounded-md shadow-sm hover:bg-brand-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green-500 disabled:opacity-50">
                            <span wire:loading.remove wire:target="submit">Aplicar Ajuste</span>
                            <span wire:loading wire:target="submit">Procesando...</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
