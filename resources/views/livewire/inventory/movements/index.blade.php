<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50 bg-opacity-50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-brand-navy-900">Historial de Movimientos</h3>
                        <p class="text-sm text-gray-500 mt-1">Registro inmutable de entradas, salidas y transferencias.</p>
                    </div>
                    
                    <!-- Filtros -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select wire:model.live="filterType" class="rounded-md border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                            <option value="">Todos los tipos</option>
                            <option value="opening">Stock Inicial</option>
                            <option value="in">Entrada</option>
                            <option value="out">Salida</option>
                            <option value="adjustment_in">Ajuste Positivo (+)</option>
                            <option value="adjustment_out">Ajuste Negativo (-)</option>
                            <option value="transfer_in">Recepción (Transferencia)</option>
                            <option value="transfer_out">Envío (Transferencia)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50 bg-opacity-50">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Almacén</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ref / Usuario</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($movements as $mov)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $mov->occurred_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $mov->product->name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $mov->product->sku }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $mov->warehouse->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($mov->type->isPositive())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-brand-green-700 border border-green-100">
                                            + {{ __($mov->type->value) }}
                                        </span>
                                    @elseif($mov->type->isNegative())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-brand-coral-500 border border-red-100">
                                            - {{ __($mov->type->value) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                            {{ __($mov->type->value) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-bold {{ $mov->type->isPositive() ? 'text-brand-green-700' : ($mov->type->isNegative() ? 'text-brand-coral-500' : 'text-gray-900') }}">
                                        {{ $mov->type->isPositive() ? '+' : ($mov->type->isNegative() ? '-' : '') }}{{ number_format($mov->quantity, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 font-medium">
                                    {{ number_format($mov->new_quantity, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $mov->reason ?? '—' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $mov->user->name ?? 'Sistema' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-base font-medium text-gray-900">Todo tranquilo por aquí</p>
                                        <p class="text-sm text-gray-500 mt-1">No encontramos movimientos con los filtros seleccionados.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($movements->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 bg-opacity-50">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
