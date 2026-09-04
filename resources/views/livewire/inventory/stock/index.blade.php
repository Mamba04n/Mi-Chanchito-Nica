<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6">
        <!-- Dashboard Resumen -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Protagonista: Valor del inventario -->
            <div class="bg-brand-navy-900 rounded-2xl shadow-sm border border-gray-800 p-6 flex flex-col justify-between text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 opacity-10">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21a9 9 0 100-18 9 9 0 000 18zm-2.75-9.75a.75.75 0 000 1.5h5.5a.75.75 0 000-1.5h-5.5zM12 7.5a.75.75 0 01.75.75v3.69l3.22 3.22a.75.75 0 11-1.06 1.06l-3.5-3.5A.75.75 0 0111.25 12V8.25A.75.75 0 0112 7.5z" clip-rule="evenodd" /></svg>
                </div>
                
                <div class="z-10">
                    <p class="text-sm text-gray-300 font-medium uppercase tracking-wider mb-2">Valor del Inventario</p>
                    <h2 class="text-4xl font-display font-bold text-white">
                        C$ {{ number_format($kpis['inventory_value'], 2) }}
                    </h2>
                    <p class="text-sm text-gray-400 mt-2">Calculado al costo promedio actual</p>
                </div>
                
                <div class="z-10 mt-6 flex gap-3">
                    <a href="{{ route('inventory.movements') }}" wire:navigate class="inline-flex items-center px-3 py-1.5 bg-white bg-opacity-10 hover:bg-opacity-20 rounded-md text-sm font-medium transition-colors">
                        Ver Movimientos →
                    </a>
                </div>
            </div>
            
            <!-- Secondary KPIs -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Productos Registrados</p>
                    <h3 class="text-2xl font-bold text-brand-navy-900">{{ $kpis['total_products'] }}</h3>
                    <p class="text-xs text-gray-400 mt-1">Con control de existencias</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border {{ $kpis['low_stock_products'] > 0 ? 'border-brand-gold-500 bg-orange-50 bg-opacity-20' : 'border-gray-100' }} p-5 flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-full {{ $kpis['low_stock_products'] > 0 ? 'bg-orange-100 text-brand-gold-500' : 'bg-green-50 text-brand-green-700' }} flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Stock Bajo</p>
                    <h3 class="text-2xl font-bold {{ $kpis['low_stock_products'] > 0 ? 'text-brand-gold-500' : 'text-brand-navy-900' }}">{{ $kpis['low_stock_products'] }}</h3>
                    <p class="text-xs text-gray-400 mt-1">Requieren atención pronto</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border {{ $kpis['out_of_stock_products'] > 0 ? 'border-brand-coral-500 bg-red-50 bg-opacity-30' : 'border-gray-100' }} p-5 flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-full {{ $kpis['out_of_stock_products'] > 0 ? 'bg-red-100 text-brand-coral-500' : 'bg-gray-50 text-gray-500' }} flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Agotados</p>
                    <h3 class="text-2xl font-bold {{ $kpis['out_of_stock_products'] > 0 ? 'text-brand-coral-500' : 'text-brand-navy-900' }}">{{ $kpis['out_of_stock_products'] }}</h3>
                    <p class="text-xs text-gray-400 mt-1">Sin unidades disponibles</p>
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
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o nombre..." 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-md leading-5 bg-white placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-brand-green-500 focus:border-brand-green-500 sm:text-sm transition-colors">
                    </div>
                    
                    <select wire:model.live="warehouse_id" class="hidden sm:block border-gray-200 rounded-md text-sm py-2 focus:ring-brand-green-500 focus:border-brand-green-500">
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
            
            @if($stocks->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 bg-opacity-50">
                    {{ $stocks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
