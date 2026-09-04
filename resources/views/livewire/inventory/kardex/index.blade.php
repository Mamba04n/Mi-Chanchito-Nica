<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6">
        
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50 bg-opacity-50">
                <h3 class="text-lg font-bold text-brand-navy-900 mb-4">Consulta de Kardex</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Producto <span class="text-brand-coral-500">*</span></label>
                        <select wire:model.live="product_id" class="w-full rounded-md border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                            <option value="">Seleccione un producto</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->sku }} - {{ $prod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Almacén <span class="text-brand-coral-500">*</span></label>
                        <select wire:model.live="warehouse_id" class="w-full rounded-md border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                            <option value="">Seleccione un almacén</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                        <input type="date" wire:model.live="dateFrom" class="w-full rounded-md border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                        <input type="date" wire:model.live="dateTo" class="w-full rounded-md border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50 bg-opacity-50">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Detalle</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-brand-green-700 uppercase tracking-wider">Entrada</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-brand-coral-500 uppercase tracking-wider">Salida</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-brand-navy-900 uppercase tracking-wider">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @if(!$product_id || !$warehouse_id)
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <p class="text-base font-medium text-gray-900">Selecciona producto y almacén</p>
                                        <p class="text-sm text-gray-500 mt-1">El Kardex requiere filtros específicos para mostrar el historial contable.</p>
                                    </div>
                                </td>
                            </tr>
                        @elseif($records->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <p class="text-base font-medium text-gray-900">Sin movimientos</p>
                                        <p class="text-sm text-gray-500 mt-1">No hay registro en las fechas seleccionadas.</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($records as $record)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $record->occurred_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $record->reason ?? $record->type->value }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $record->user->name ?? 'Sistema' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($record->type->isPositive())
                                            <span class="text-sm font-medium text-brand-green-700">{{ number_format($record->quantity, 2) }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($record->type->isNegative())
                                            <span class="text-sm font-medium text-brand-coral-500">{{ number_format($record->quantity, 2) }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-brand-navy-900 bg-gray-50 bg-opacity-50">
                                        {{ number_format($record->new_quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @if($product_id && $warehouse_id && $records->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 bg-opacity-50">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
