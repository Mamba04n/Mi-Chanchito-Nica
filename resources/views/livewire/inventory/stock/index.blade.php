<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

        <!-- Dashboard KPIs (Pulse Style) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            
            <!-- KPI: Valor del Inventario -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-medium text-gray-900">Valor de Inventario</h3>
                    <a href="{{ route('inventory.kardex') }}" class="text-xs text-brand-green-700 font-medium hover:underline flex items-center gap-1">
                        Ver kardex <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
                <h2 class="text-3xl font-display font-bold text-gray-900 mb-4">
                    C$ {{ number_format($indicators['approximate_inventory_value'], 2) }}
                </h2>
                <div>
                    <div class="flex items-center text-xs text-gray-500 mb-2">
                        <span class="text-brand-green-700 font-medium flex items-center mr-1">
                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            Total actual
                        </span>
                        <span>Costo promedio</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-brand-green-700 h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <!-- KPI: Productos Registrados -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-medium text-gray-900">Productos Registrados</h3>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $indicators['total_inventoriable_products'] }}
                </h2>
                <div>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                        <span>Con control de inventario</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <!-- KPI: Stock Bajo -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-medium text-gray-900">Stock Bajo</h3>
                    @if($indicators['products_low_stock'] > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-orange-100 text-brand-gold-500">Atención</span>
                    @endif
                </div>
                <h2 class="text-3xl font-bold {{ $indicators['products_low_stock'] > 0 ? 'text-brand-gold-500' : 'text-gray-900' }} mb-4">
                    {{ $indicators['products_low_stock'] }}
                </h2>
                <div>
                    <div class="flex items-center text-xs text-gray-500 mb-2">
                        @if($indicators['products_low_stock'] > 0)
                            <span class="text-brand-coral-500 font-medium mr-1">Reordenar pronto</span>
                        @else
                            <span class="text-brand-green-700 font-medium mr-1">Niveles óptimos</span>
                        @endif
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="{{ $indicators['products_low_stock'] > 0 ? 'bg-brand-gold-500' : 'bg-brand-green-500' }} h-1.5 rounded-full" style="width: {{ $indicators['total_inventoriable_products'] > 0 ? min(100, ($indicators['products_low_stock'] / $indicators['total_inventoriable_products']) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- KPI: Agotados -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-medium text-gray-900">Agotados</h3>
                    @if($indicators['products_out_of_stock'] > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-100 text-brand-coral-500">Crítico</span>
                    @endif
                </div>
                <h2 class="text-3xl font-bold {{ $indicators['products_out_of_stock'] > 0 ? 'text-brand-coral-500' : 'text-gray-900' }} mb-4">
                    {{ $indicators['products_out_of_stock'] }}
                </h2>
                <div>
                    <div class="flex items-center text-xs text-gray-500 mb-2">
                        <span>Productos sin disponibilidad</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="{{ $indicators['products_out_of_stock'] > 0 ? 'bg-brand-coral-500' : 'bg-gray-300' }} h-1.5 rounded-full" style="width: {{ $indicators['total_inventoriable_products'] > 0 ? min(100, ($indicators['products_out_of_stock'] / $indicators['total_inventoriable_products']) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existencias (Tabla Principal) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 bg-opacity-50">
                <div>
                    <h3 class="text-lg font-bold text-brand-navy-900">Existencias Actuales</h3>
                    <p class="text-sm text-gray-500 mt-1">Listado detallado por almacén y estado de stock.</p>
                </div>
                
                <!-- Búsqueda y Filtros -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="searchProduct" type="text" placeholder="Buscar por código o nombre..." 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-md leading-5 bg-white placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-brand-green-500 focus:border-brand-green-500 sm:text-sm transition-colors">
                    </div>
                    
                    <select wire:model.live="filterWarehouseId" class="hidden sm:block border-gray-200 rounded-md text-sm py-2 focus:ring-brand-green-500 focus:border-brand-green-500">
                        <option value="">Todos los almacenes</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50 bg-opacity-50">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Almacén</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Mínimo</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-brand-navy-900 uppercase tracking-wider">Disponible</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($stocks as $stock)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 group-hover:text-brand-green-700 transition-colors">{{ $stock->product->name }}</span>
                                        <span class="text-xs text-gray-400 mt-0.5">{{ $stock->product->sku }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $stock->warehouse->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                    {{ number_format($stock->minimum_stock, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($stock->available_quantity, 2) }}</span>
                                    @if($stock->reserved_quantity > 0)
                                        <div class="text-[10px] text-gray-400 mt-1" title="Reservado">(-{{ number_format($stock->reserved_quantity, 2) }})</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($stock->available_quantity <= 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-brand-coral-500 border border-red-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-coral-500"></span> Agotado
                                        </span>
                                    @elseif($stock->available_quantity <= $stock->minimum_stock)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-brand-gold-500 border border-orange-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold-500"></span> Stock Bajo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-brand-green-700 border border-green-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-green-500"></span> Normal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        <p class="text-base font-medium text-gray-900">No hay existencias registradas</p>
                                        <p class="text-sm text-gray-500 mt-1">No se encontraron productos con seguimiento de inventario para los filtros actuales.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- No pagination as backend returns full collection -->
        </div>
    </div>
</div>
