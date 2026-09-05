<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6 max-w-3xl mx-auto">
        
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-brand-soft-border">
            <div class="p-6 border-b border-brand-soft-border bg-brand-soft-hoverBg bg-opacity-50">
                <h3 class="text-lg font-bold text-brand-navy-900">Ajuste de Inventario</h3>
                <p class="text-sm text-brand-soft-textSec mt-1">Registra la cantidad física contada para ajustar diferencias en el sistema.</p>
            </div>
            
            <form wire:submit.prevent="submit" class="p-6 space-y-6">
                @if (session()->has('error'))
                    <div class="p-4 bg-red-50 border border-red-100 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-brand-coral-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-brand-coral-500 font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                        <select wire:model.live="product_id" required class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                            <option value="">Seleccione un producto</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Almacén</label>
                        <select wire:model.live="warehouse_id" required class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                            <option value="">Seleccione un almacén</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="bg-brand-soft-hoverBg rounded-xl border border-brand-soft-border p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 text-center relative overflow-hidden">
                    <div class="flex-1">
                        <p class="text-[10px] text-brand-soft-textSec font-bold uppercase tracking-wider">Stock Sistema</p>
                        <p class="text-3xl font-display text-brand-soft-textMain mt-1">{{ number_format($currentStock, 2) }}</p>
                    </div>
                    
                    <div class="flex-1 border-t border-b border-gray-200 py-4 md:border-y-0 md:border-x md:px-4">
                        <p class="text-[10px] text-brand-green-700 font-bold uppercase tracking-wider">Conteo Físico (Real)</p>
                        <div class="mt-2 flex justify-center">
                            <input type="number" step="0.01" wire:model.live="real_quantity" required placeholder="0.00" 
                                class="w-32 text-center rounded-lg border-gray-300 shadow-sm focus:ring-brand-green-500 focus:border-brand-green-500 text-2xl font-bold text-brand-navy-900 py-2">
                        </div>
                        @error('real_quantity') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="flex-1">
                        <p class="text-[10px] text-brand-soft-textSec font-bold uppercase tracking-wider">Diferencia</p>
                        <p class="text-3xl font-display mt-1 {{ $difference > 0 ? 'text-brand-green-700' : ($difference < 0 ? 'text-brand-coral-500' : 'text-brand-soft-textMain') }}">
                            {{ $difference > 0 ? '+' : '' }}{{ number_format($difference, 2) }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo</label>
                        <select wire:model="reason" required class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                            <option value="">Seleccione el motivo</option>
                            <option value="Conteo físico">Conteo físico</option>
                            <option value="Daño o merma">Daño o merma</option>
                            <option value="Pérdida">Pérdida</option>
                            <option value="Vencimiento">Vencimiento</option>
                            <option value="Corrección de sistema">Corrección de sistema</option>
                            <option value="Otro">Otro</option>
                        </select>
                        @error('reason') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <input type="text" wire:model="notes" placeholder="Opcional..." class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-brand-soft-border">
                    <a href="{{ route('inventory.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-brand-soft-hoverBg hover:text-brand-soft-textMain transition-colors mr-3">
                        Cancelar
                    </a>
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="inline-flex justify-center px-5 py-2 text-sm font-bold text-white bg-brand-green-700 border border-transparent rounded-lg shadow-sm hover:bg-brand-soft-activeBg0 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green-500 disabled:opacity-50 transition-colors">
                        <span wire:loading.remove wire:target="submit">Aplicar Ajuste</span>
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
