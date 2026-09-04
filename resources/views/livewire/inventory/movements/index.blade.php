<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-brand-navy-900">Movimientos de Inventario</h3>
                            <p class="text-sm text-gray-500 mt-1">Historial completo e inmutable de entradas, salidas y transferencias.</p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <select wire:model.live="filterType" class="rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 text-sm">
                                <option value="">Todos los tipos</option>
                                <option value="opening">Stock Inicial</option>
                                <option value="in">Entrada</option>
                                <option value="out">Salida</option>
                                <option value="adjustment_in">Ajuste Positivo (+)</option>
                                <option value="adjustment_out">Ajuste Negativo (-)</option>
                                <option value="transfer_in">Transferencia (Entrada)</option>
                                <option value="transfer_out">Transferencia (Salida)</option>
                            </select>

                            <select wire:model.live="filterWarehouseId" class="rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 text-sm">
                                <option value="">Todos los almacenes</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Almacén</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Movimiento</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referencia</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($movements as $movement)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movement->occurred_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $typeStr = $movement->type->value;
                                                $bgClass = 'bg-gray-100 text-gray-800';
                                                
                                                if (in_array($typeStr, ['in', 'opening', 'adjustment_in', 'transfer_in'])) {
                                                    $bgClass = 'bg-brand-green-500 bg-opacity-20 text-brand-green-700';
                                                } elseif (in_array($typeStr, ['out', 'adjustment_out', 'transfer_out'])) {
                                                    $bgClass = 'bg-brand-coral-500 bg-opacity-20 text-brand-coral-500';
                                                }
                                                
                                                $labels = [
                                                    'opening' => 'Inicial',
                                                    'in' => 'Entrada',
                                                    'out' => 'Salida',
                                                    'adjustment_in' => 'Ajuste (+)',
                                                    'adjustment_out' => 'Ajuste (-)',
                                                    'transfer_in' => 'Transf (In)',
                                                    'transfer_out' => 'Transf (Out)'
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bgClass }}">
                                                {{ $labels[$typeStr] ?? $typeStr }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $movement->product->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $movement->product->sku }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $movement->warehouse->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $movement->type->isPositive() ? 'text-brand-green-700' : 'text-brand-coral-500' }}">
                                            {{ $movement->type->isPositive() ? '+' : '-' }}{{ number_format($movement->quantity, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div>{{ $movement->reason ?? '-' }}</div>
                                            @if($movement->notes)
                                                <div class="text-xs text-gray-400 truncate w-48" title="{{ $movement->notes }}">{{ $movement->notes }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay movimientos registrados</h3>
                                            <p class="mt-1 text-sm text-gray-500">Los movimientos de stock se listarán aquí automáticamente.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($movements->hasPages())
                        <div class="mt-4 border-t border-gray-200 pt-4">
                            {{ $movements->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
