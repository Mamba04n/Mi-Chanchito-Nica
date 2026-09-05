<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-brand-soft-border overflow-hidden">
            <div class="p-5 border-b border-brand-soft-border bg-white">
                <h3 class="text-lg font-bold text-brand-soft-textMain mb-4">Consulta de Kardex</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider mb-1">Producto <span class="text-brand-coral-500">*</span></label>
                        <select wire:model.live="product_id" class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500 bg-brand-soft-hoverBg focus:bg-white">
                            <option value="">Seleccione un producto</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider mb-1">Almacén <span class="text-brand-coral-500">*</span></label>
                        <select wire:model.live="warehouse_id" class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500 bg-brand-soft-hoverBg focus:bg-white">
                            <option value="">Seleccione un almacén</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider mb-1">Desde</label>
                        <input type="date" wire:model.live="dateFrom" class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500 bg-brand-soft-hoverBg focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider mb-1">Hasta</label>
                        <input type="date" wire:model.live="dateTo" class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500 bg-brand-soft-hoverBg focus:bg-white">
                    </div>
                </div>
            </div>

            <!-- Mobile: Timeline Cards -->
            <div class="md:hidden divide-y divide-gray-100">
                @if(!$product_id || !$warehouse_id)
                    <div class="p-8 text-center">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="text-sm font-medium text-brand-soft-textMain">Selecciona producto y almacén</p>
                    </div>
                @elseif($records->isEmpty())
                    <div class="p-8 text-center">
                        <p class="text-sm font-medium text-brand-soft-textMain">Sin movimientos</p>
                    </div>
                @else
                    @foreach($records as $record)
                        <div class="p-4 bg-white relative">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="text-sm font-bold text-brand-soft-textMain">{{ $record->reason ?? $record->type->value }}</h4>
                                    <p class="text-xs text-brand-soft-textSec">{{ $record->occurred_at->format('d/m/Y h:i a') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Saldo</p>
                                    <p class="text-base font-bold text-brand-soft-textMain">{{ number_format($record->new_quantity, 2) }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4 border-t border-gray-50 pt-2 mt-2">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Entrada</p>
                                    @if($record->type->isPositive())
                                        <p class="text-sm font-bold text-brand-green-700">+{{ number_format($record->quantity, 2) }}</p>
                                    @else
                                        <p class="text-sm text-gray-300">-</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Salida</p>
                                    @if($record->type->isNegative())
                                        <p class="text-sm font-bold text-brand-coral-500">-{{ number_format($record->quantity, 2) }}</p>
                                    @else
                                        <p class="text-sm text-gray-300">-</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Desktop: Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-brand-soft-hoverBg">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Detalle</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-brand-green-700 uppercase tracking-wider">Entrada</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-brand-coral-500 uppercase tracking-wider">Salida</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-brand-soft-textMain uppercase tracking-wider">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @if(!$product_id || !$warehouse_id)
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <p class="text-sm font-medium text-brand-soft-textMain">Selecciona producto y almacén</p>
                                    </div>
                                </td>
                            </tr>
                        @elseif($records->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <p class="text-sm font-medium text-brand-soft-textMain">Sin movimientos</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($records as $record)
                                <tr class="hover:bg-brand-soft-hoverBg transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-soft-textSec">
                                        {{ $record->occurred_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-brand-soft-textMain">{{ $record->reason ?? $record->type->value }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $record->user->name ?? 'Sistema' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($record->type->isPositive())
                                            <span class="text-sm font-bold text-brand-green-700">+{{ number_format($record->quantity, 2) }}</span>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($record->type->isNegative())
                                            <span class="text-sm font-bold text-brand-coral-500">-{{ number_format($record->quantity, 2) }}</span>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-brand-soft-textMain bg-brand-soft-hoverBg bg-opacity-50">
                                        {{ number_format($record->new_quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @if($product_id && $warehouse_id && $records->hasPages())
                <div class="px-6 py-4 border-t border-brand-soft-border bg-white">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
