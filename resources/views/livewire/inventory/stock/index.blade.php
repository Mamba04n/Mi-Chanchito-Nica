<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Dashboard Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex flex-col justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Valor del inventario</h3>
                        <p class="text-3xl font-display text-brand-navy-900 mt-2">C$ {{ number_format($indicators['approximate_inventory_value'], 2) }}</p>
                    </div>
                    <div class="mt-4 text-xs text-gray-400">al costo actual</div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex flex-col justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Productos Inventariables</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $indicators['total_inventoriable_products'] }}</p>
                    </div>
                    <div class="mt-4 text-xs text-brand-green-700 font-medium">Activos en catálogo</div>
                </div>

                <div class="bg-brand-coral-500 bg-opacity-10 rounded-lg shadow-sm border border-brand-coral-500 border-opacity-30 p-5 flex flex-col justify-between">
                    <div>
                        <h3 class="text-brand-coral-500 text-sm font-bold uppercase tracking-wider">Stock Bajo</h3>
                        <p class="text-3xl font-bold text-brand-coral-500 mt-2">{{ $indicators['products_low_stock'] }}</p>
                    </div>
                    <div class="mt-4 text-xs text-brand-coral-500 font-medium">productos requieren atención</div>
                </div>

                <div class="bg-red-50 rounded-lg shadow-sm border border-red-200 p-5 flex flex-col justify-between">
                    <div>
                        <h3 class="text-red-600 text-sm font-bold uppercase tracking-wider">Agotados</h3>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $indicators['products_out_of_stock'] }}</p>
                    </div>
                    <div class="mt-4 text-xs text-red-600 font-medium">productos sin existencias</div>
                </div>
            </div>

            <!-- Existencias Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
                        <h3 class="text-lg font-bold text-brand-navy-900">Control de Existencias</h3>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Filters -->
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="searchProduct" placeholder="Buscar producto o SKU..." 
                                       class="rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 text-sm w-full sm:w-64">
                            </div>
                            
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Almacén</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Disponible</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Físico (Real)</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Mínimo</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($stocks as $stock)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $stock->product->name }}</div>
                                                    <div class="text-xs text-gray-500">SKU: {{ $stock->product->sku }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $stock->warehouse->name }}</div>
                                            @if($stock->warehouse->is_default)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    Principal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                            {{ number_format($stock->available_quantity, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ number_format($stock->quantity, 2) }}
                                            @if($stock->reserved_quantity > 0)
                                                <span class="block text-xs text-brand-gold-500 mt-1">(-{{ number_format($stock->reserved_quantity, 2) }} reservado)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ $stock->minimum_stock ? number_format($stock->minimum_stock, 2) : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($stock->quantity <= 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Agotado
                                                </span>
                                            @elseif($stock->minimum_stock !== null && $stock->quantity <= $stock->minimum_stock)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-coral-500 bg-opacity-20 text-brand-coral-500">
                                                    Bajo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-green-500 bg-opacity-20 text-brand-green-700">
                                                    Normal
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay existencias</h3>
                                            <p class="mt-1 text-sm text-gray-500">Comienza registrando un movimiento de entrada o transferencia.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
