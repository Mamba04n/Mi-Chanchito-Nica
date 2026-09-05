<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6 max-w-4xl mx-auto">
        
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-brand-soft-border">
            <div class="p-6 border-b border-brand-soft-border flex items-center space-x-3 bg-brand-soft-hoverBg bg-opacity-50">
                <div class="p-2 bg-brand-navy-900 text-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-brand-navy-900">Transferir Existencias</h3>
                    <p class="text-sm text-brand-soft-textSec mt-1">Mueve productos de un almacén a otro de forma segura.</p>
                </div>
            </div>
            
            <form wire:submit.prevent="submit" class="p-6 space-y-8">
                @if (session()->has('error'))
                    <div class="p-4 bg-red-50 border border-red-100 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-brand-coral-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-brand-coral-500 font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start relative">
                    
                    <!-- Origen -->
                    <div class="bg-brand-soft-hoverBg p-6 rounded-2xl border border-brand-soft-border relative z-10">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-200">
                            <span class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 text-xs flex items-center justify-center font-bold">1</span>
                            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Origen</h4>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Almacén Origen</label>
                                <select wire:model.live="source_warehouse_id" required class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                                    <option value="">Seleccione origen</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                                @error('source_warehouse_id') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Producto a Transferir</label>
                                <select wire:model.live="product_id" required class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                                    <option value="">Seleccione producto</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="pt-2 bg-white rounded-lg p-3 border border-brand-soft-border flex justify-between items-center">
                                <span class="text-xs font-bold text-brand-soft-textSec uppercase">Disponible</span>
                                <span class="text-lg font-bold {{ $available_quantity > 0 ? 'text-brand-green-700' : 'text-gray-400' }}">{{ number_format($available_quantity, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Flecha visual (Desktop) -->
                    <div class="hidden lg:flex absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-white border border-brand-soft-border rounded-full items-center justify-center shadow-sm z-20">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>

                    <!-- Flecha visual (Mobile) -->
                    <div class="flex lg:hidden justify-center -my-6 relative z-20">
                        <div class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-brand-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </div>
                    </div>

                    <!-- Destino -->
                    <div class="bg-brand-soft-activeBg bg-opacity-30 p-6 rounded-2xl border border-brand-green-500 border-opacity-20 relative z-10">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-brand-green-500 border-opacity-20">
                            <span class="w-6 h-6 rounded-full bg-brand-soft-activeBg0 text-white text-xs flex items-center justify-center font-bold">2</span>
                            <h4 class="text-sm font-bold text-brand-green-700 uppercase tracking-wider">Destino</h4>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Almacén Destino</label>
                                <select wire:model="destination_warehouse_id" required class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                                    <option value="">Seleccione destino</option>
                                    @foreach($warehouses as $wh)
                                        @if($wh->id != $source_warehouse_id)
                                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('destination_warehouse_id') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad a Transferir</label>
                                <input type="number" step="0.01" wire:model="quantity" required placeholder="0.00" class="w-full rounded-lg border-brand-green-500 shadow-sm focus:ring-brand-green-500 focus:border-brand-green-500 text-lg font-bold py-2">
                                @error('quantity') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                                <input type="text" wire:model="notes" placeholder="Motivo de la transferencia..." class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-brand-soft-border">
                    <a href="{{ route('inventory.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-brand-soft-hoverBg hover:text-brand-soft-textMain transition-colors mr-3">
                        Cancelar
                    </a>
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="inline-flex justify-center px-5 py-2 text-sm font-bold text-white bg-brand-navy-900 border border-transparent rounded-lg shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-navy-900 disabled:opacity-50 transition-colors">
                        <span wire:loading.remove wire:target="submit">Confirmar Transferencia</span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Procesando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
