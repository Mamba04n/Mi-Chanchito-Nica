<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-brand-soft-border overflow-hidden">
            <div class="p-5 border-b border-brand-soft-border bg-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-brand-soft-textMain">Historial de Movimientos</h3>
                        <p class="text-sm text-brand-soft-textSec mt-1">Registro inmutable de entradas, salidas y transferencias.</p>
                    </div>
                    
                    <!-- Filtros -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select wire:model.live="filterType" class="rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500 bg-brand-soft-hoverBg focus:bg-white transition-colors">
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

            <!-- Mobile: List Cards -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($movements as $mov)
                    <div class="p-4 bg-white">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="text-sm font-bold text-brand-soft-textMain">{{ $mov->product->name }}</h4>
                                <p class="text-xs text-brand-soft-textSec">{{ $mov->product->sku }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-base font-bold {{ $mov->type->isPositive() ? 'text-brand-green-700' : ($mov->type->isNegative() ? 'text-brand-coral-500' : 'text-brand-soft-textMain') }}">
                                    {{ $mov->type->isPositive() ? '+' : ($mov->type->isNegative() ? '-' : '') }}{{ number_format($mov->quantity, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-3 text-[10px]">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded font-medium {{ $mov->type->isPositive() ? 'bg-green-50 text-brand-green-700' : ($mov->type->isNegative() ? 'bg-red-50 text-brand-coral-500' : 'bg-gray-100 text-gray-700') }}">
                                {{ __($mov->type->value) }}
                            </span>
                            <span class="text-brand-soft-textSec">{{ $mov->occurred_at->format('d M, h:i a') }}</span>
                        </div>
                        <div class="flex justify-between items-end border-t border-gray-50 pt-2">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Almacén</p>
                                <p class="text-xs text-gray-700 font-medium">{{ $mov->warehouse->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 uppercase font-semibold">Saldo final</p>
                                <p class="text-sm text-brand-soft-textMain font-bold">{{ number_format($mov->new_quantity, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-sm font-medium text-brand-soft-textMain">Sin movimientos</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop: Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-brand-soft-hoverBg">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Producto</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Almacén</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Tipo</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Cantidad</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Saldo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-brand-soft-textSec uppercase tracking-wider">Ref / Usuario</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($movements as $mov)
                            <tr class="hover:bg-brand-soft-hoverBg transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-soft-textSec">
                                    {{ $mov->occurred_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-brand-soft-textMain">{{ $mov->product->name }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $mov->product->sku }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $mov->warehouse->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($mov->type->isPositive())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-green-50 text-brand-green-700">
                                            + {{ __($mov->type->value) }}
                                        </span>
                                    @elseif($mov->type->isNegative())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-50 text-brand-coral-500">
                                            - {{ __($mov->type->value) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">
                                            {{ __($mov->type->value) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-bold {{ $mov->type->isPositive() ? 'text-brand-green-700' : ($mov->type->isNegative() ? 'text-brand-coral-500' : 'text-brand-soft-textMain') }}">
                                        {{ $mov->type->isPositive() ? '+' : ($mov->type->isNegative() ? '-' : '') }}{{ number_format($mov->quantity, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-brand-soft-textSec font-medium">
                                    {{ number_format($mov->new_quantity, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-brand-soft-textMain">{{ $mov->reason ?? '-' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $mov->user->name ?? 'Sistema' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-sm font-medium text-brand-soft-textMain">No hay movimientos</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($movements->hasPages())
                <div class="px-6 py-4 border-t border-brand-soft-border bg-white">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
