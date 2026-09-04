<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex items-center space-x-3">
                    <div class="p-2 bg-brand-green-500 bg-opacity-10 rounded-full">
                        <svg class="w-6 h-6 text-brand-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-brand-navy-900">Transferir Existencias</h3>
                        <p class="text-sm text-gray-500 mt-1">Mueve productos de un almacén a otro.</p>
                    </div>
                </div>
                
                <form wire:submit.prevent="submit" class="p-6 space-y-6">
                    @if (session()->has('error'))
                        <div class="p-4 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-600">{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        
                        <!-- Origen -->
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Desde (Origen)</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Almacén Origen</label>
                                    <select wire:model.live="source_warehouse_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                        <option value="">Seleccione origen</option>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('source_warehouse_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Producto a Transferir</label>
                                    <select wire:model.live="product_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                        <option value="">Seleccione producto</option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="pt-2">
                                    <span class="text-sm text-gray-500">Disponible para transferir:</span>
                                    <span class="text-lg font-bold {{ $available_quantity > 0 ? 'text-brand-green-700' : 'text-red-500' }} ml-2">{{ number_format($available_quantity, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Destino -->
                        <div class="bg-white p-5 rounded-lg border border-brand-green-500 border-opacity-30 shadow-sm relative">
                            <!-- Flecha visual -->
                            <div class="hidden md:flex absolute -left-6 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white border border-gray-200 rounded-full items-center justify-center shadow-sm z-10">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>

                            <h4 class="text-sm font-bold text-brand-green-700 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Hacia (Destino)</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Almacén Destino</label>
                                    <select wire:model="destination_warehouse_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                        <option value="">Seleccione destino</option>
                                        @foreach($warehouses as $wh)
                                            @if($wh->id != $source_warehouse_id)
                                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('destination_warehouse_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad a Transferir</label>
                                    <input type="number" step="0.01" wire:model="quantity" required placeholder="0.00" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                    @error('quantity') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones / Referencia</label>
                                    <input type="text" wire:model="notes" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                        <a href="{{ route('inventory.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 mr-3">
                            Cancelar
                        </a>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-brand-navy-900 border border-transparent rounded-md shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-navy-900 disabled:opacity-50">
                            <span wire:loading.remove wire:target="submit">Confirmar Transferencia</span>
                            <span wire:loading wire:target="submit">Procesando...</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
