<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6">
        <!-- ======================================================= -->
        <!-- ROW 1: KPIs Compactos y Jerarquizados                  -->
        <!-- ======================================================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Hero KPI: Valor -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-brand-soft-border p-5 flex flex-col justify-center relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-brand-soft-textSec uppercase tracking-wider mb-1">Valor Aproximado del Inventario</p>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-soft-textMain">
                        C$ {{ number_format($indicators['approximate_inventory_value'], 2) }}
                    </h2>
                    <div class="mt-3 flex items-center gap-3">
                        <a href="{{ route('inventory.kardex') }}" class="inline-flex items-center gap-1 px-3 py-1 bg-brand-soft-hoverBg text-brand-soft-textMain hover:bg-brand-soft-activeBg transition-colors rounded-lg text-xs font-semibold border border-brand-soft-borderDark">
                            Ver detalles en Kardex <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
                <!-- Decoración sutil -->
                <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-brand-soft-activeBg/50 to-transparent pointer-events-none"></div>
                <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-brand-soft-accent opacity-5 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
            </div>

            <!-- Productos Registrados -->
            <div class="bg-white rounded-xl shadow-sm border border-brand-soft-border p-4 flex flex-col justify-between">
                <p class="text-[10px] font-bold text-brand-soft-textSec uppercase tracking-wider">Registrados</p>
                <div class="mt-1">
                    <h3 class="text-2xl font-bold text-brand-soft-textMain">{{ $indicators['total_inventoriable_products'] }}</h3>
                    <p class="text-[11px] text-brand-soft-textSec mt-1">Con control de inventario</p>
                </div>
                <div class="w-full bg-brand-soft-hoverBg rounded-full h-1 mt-3">
                    <div class="bg-brand-soft-accent h-1 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            <!-- Alertas Agrupadas (Bajo + Agotado) -->
            <div class="bg-white rounded-xl shadow-sm border border-brand-soft-border p-4 flex flex-col justify-between">
                <p class="text-[10px] font-bold text-brand-soft-textSec uppercase tracking-wider">Estado Crítico</p>
                <div class="mt-1 flex items-end gap-3">
                    <div>
                        <h3 class="text-2xl font-bold {{ $indicators['products_out_of_stock'] > 0 ? 'text-brand-coral-500' : 'text-brand-soft-textMain' }} leading-none">{{ $indicators['products_out_of_stock'] }}</h3>
                        <p class="text-[10px] text-brand-soft-textSec mt-1 font-semibold">Agotados</p>
                    </div>
                    <div class="w-px h-8 bg-brand-soft-borderDark"></div>
                    <div>
                        <h3 class="text-xl font-bold {{ $indicators['products_low_stock'] > 0 ? 'text-brand-gold-500' : 'text-brand-soft-textMain' }} leading-none">{{ $indicators['products_low_stock'] }}</h3>
                        <p class="text-[10px] text-brand-soft-textSec mt-1 font-semibold">Stock Bajo</p>
                    </div>
                </div>
                <div class="w-full bg-brand-soft-hoverBg rounded-full h-1 mt-3 flex">
                    @php 
                        $totalAlerts = $indicators['products_low_stock'] + $indicators['products_out_of_stock'];
                        $total = $indicators['total_inventoriable_products'] > 0 ? $indicators['total_inventoriable_products'] : 1;
                        $outPct = min(100, ($indicators['products_out_of_stock'] / $total) * 100);
                        $lowPct = min(100 - $outPct, ($indicators['products_low_stock'] / $total) * 100);
                    @endphp
                    <div class="bg-brand-coral-500 h-1" style="width: {{ $outPct }}%"></div>
                    <div class="bg-brand-gold-500 h-1" style="width: {{ $lowPct }}%"></div>
                </div>
            </div>
        </div>

        <!-- ======================================================= -->
        <!-- ROW 2: Alertas (Izquierda) + Existencias (Derecha)     -->
        <!-- ======================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- Izquierda: Productos que requieren atención -->
            <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-brand-soft-border flex flex-col h-full overflow-hidden">
                <div class="p-4 border-b border-brand-soft-border bg-brand-soft-warnBg/30 flex items-center gap-2">
                    <span class="flex w-2 h-2 rounded-full bg-brand-coral-500 animate-pulse"></span>
                    <h3 class="text-sm font-bold text-brand-soft-textMain">Requieren Atención</h3>
                </div>
                
                <div class="p-0 divide-y divide-brand-soft-border overflow-y-auto max-h-[400px]">
                    @php
                        $attentionNeeded = $stocks->filter(function($s) {
                            return $s->available_quantity <= $s->minimum_stock || $s->available_quantity <= 0;
                        })->sortBy('available_quantity')->take(8);
                    @endphp

                    @forelse($attentionNeeded as $alert)
                        <div class="p-3 hover:bg-brand-soft-hoverBg transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-sm font-bold text-brand-soft-textMain truncate pr-2">{{ $alert->product->name }}</p>
                                @if($alert->available_quantity <= 0)
                                    <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-red-50 text-brand-coral-500 border border-red-100">Agotado</span>
                                @else
                                    <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-orange-50 text-brand-gold-500 border border-orange-100">Bajo</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-brand-soft-textSec mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                {{ $alert->warehouse->name }}
                            </p>
                            <div class="flex items-center text-[11px] font-medium text-brand-soft-textMain">
                                <span><strong class="{{ $alert->available_quantity <= 0 ? 'text-brand-coral-500' : 'text-brand-soft-textMain' }}">{{ number_format($alert->available_quantity, 2) }}</strong> disp.</span>
                                <span class="mx-1 text-gray-300">·</span>
                                <span class="text-brand-soft-textSec">mínimo {{ number_format($alert->minimum_stock, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-brand-soft-activeBg text-brand-soft-accent flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-xs font-medium text-brand-soft-textMain">Todo en orden</p>
                            <p class="text-[10px] text-brand-soft-textSec">Ningún producto requiere atención inmediata.</p>
                        </div>
                    @endforelse
                </div>
                
                @if($attentionNeeded->count() > 0)
                <div class="p-3 border-t border-brand-soft-border bg-brand-soft-hoverBg text-center">
                    <a href="{{ route('inventory.adjustments') }}" class="text-[11px] font-bold text-brand-soft-accent hover:text-brand-green-700 uppercase tracking-wide">Realizar ajuste</a>
                </div>
                @endif
            </div>

            <!-- Derecha: Existencias Actuales (Preview compacta) -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-brand-soft-border flex flex-col h-full overflow-hidden">
                <div class="p-4 border-b border-brand-soft-border flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-white">
                    <div>
                        <h3 class="text-sm font-bold text-brand-soft-textMain">Existencias Actuales</h3>
                    </div>
                    <!-- Buscador súper compacto -->
                    <div class="flex gap-2">
                        <select wire:model.live="filterWarehouseId" class="text-xs py-1.5 pl-2 pr-6 border-gray-200 rounded-lg bg-brand-soft-hoverBg focus:ring-brand-green-500">
                            <option value="">Almacenes</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        <div class="relative w-full sm:w-48">
                            <input wire:model.live.debounce.300ms="searchProduct" type="text" placeholder="Buscar..." 
                                class="w-full pl-8 pr-2 py-1.5 border border-gray-200 rounded-lg text-xs bg-brand-soft-hoverBg focus:bg-white focus:ring-1 focus:ring-brand-green-500">
                            <svg class="absolute left-2.5 top-2 w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>
                </div>

                <!-- Tabla compacta (Desktop) -->
                <div class="hidden sm:block overflow-x-auto flex-1 max-h-[400px]">
                    <table class="min-w-full divide-y divide-brand-soft-border">
                        <thead class="bg-brand-soft-hoverBg/50 sticky top-0 z-10 backdrop-blur-sm">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left text-[10px] font-bold text-brand-soft-textSec uppercase">Producto</th>
                                <th scope="col" class="px-4 py-2 text-left text-[10px] font-bold text-brand-soft-textSec uppercase">Almacén</th>
                                <th scope="col" class="px-4 py-2 text-right text-[10px] font-bold text-brand-soft-textMain uppercase">Disp.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-soft-border bg-white">
                            @forelse($stocks->take(10) as $stock)
                                <tr class="hover:bg-brand-soft-hoverBg transition-colors group">
                                    <td class="px-4 py-2">
                                        <p class="text-xs font-bold text-brand-soft-textMain truncate max-w-[180px]">{{ $stock->product->name }}</p>
                                        <p class="text-[9px] text-brand-soft-textSec">{{ $stock->product->sku }}</p>
                                    </td>
                                    <td class="px-4 py-2 text-xs text-brand-soft-textSec">
                                        {{ $stock->warehouse->name }}
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <span class="text-xs font-bold {{ $stock->available_quantity <= $stock->minimum_stock ? 'text-brand-coral-500' : 'text-brand-soft-textMain' }}">{{ number_format($stock->available_quantity, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-xs text-brand-soft-textSec">No se encontraron resultados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Lista compacta (Mobile) -->
                <div class="sm:hidden divide-y divide-brand-soft-border max-h-[350px] overflow-y-auto">
                    @forelse($stocks->take(10) as $stock)
                        <div class="p-3">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-xs font-bold text-brand-soft-textMain truncate pr-2">{{ $stock->product->name }}</p>
                                <span class="text-xs font-bold {{ $stock->available_quantity <= $stock->minimum_stock ? 'text-brand-coral-500' : 'text-brand-soft-textMain' }}">{{ number_format($stock->available_quantity, 2) }}</span>
                            </div>
                            <p class="text-[10px] text-brand-soft-textSec">{{ $stock->warehouse->name }}</p>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-brand-soft-textSec">No se encontraron resultados</div>
                    @endforelse
                </div>

                <div class="p-3 border-t border-brand-soft-border bg-brand-soft-hoverBg text-center">
                    <button class="text-[11px] font-bold text-brand-soft-textMain hover:text-brand-green-700 uppercase tracking-wide">
                        Ver listado completo
                    </button>
                </div>
            </div>
        </div>

        <!-- ======================================================= -->
        <!-- ROW 3: Micro Reportes Visuales                         -->
        <!-- ======================================================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Micro Reporte 1: Distribución del estado del stock -->
            <div class="bg-white rounded-xl shadow-sm border border-brand-soft-border p-5">
                <h3 class="text-sm font-bold text-brand-soft-textMain mb-4">Salud del Inventario</h3>
                
                @php
                    $normal = $indicators['products_with_stock'] - $indicators['products_low_stock'];
                    $low = $indicators['products_low_stock'];
                    $out = $indicators['products_out_of_stock'];
                    $tot = $normal + $low + $out;
                    if($tot == 0) $tot = 1; // prevent division by zero
                @endphp

                <!-- Barra acumulativa -->
                <div class="w-full h-3 rounded-full overflow-hidden flex mb-4 border border-brand-soft-borderDark">
                    <div class="bg-brand-soft-accent transition-all duration-500" style="width: {{ ($normal / $tot) * 100 }}%"></div>
                    <div class="bg-brand-gold-500 transition-all duration-500" style="width: {{ ($low / $tot) * 100 }}%"></div>
                    <div class="bg-brand-coral-500 transition-all duration-500" style="width: {{ ($out / $tot) * 100 }}%"></div>
                </div>

                <!-- Leyenda compacta -->
                <div class="grid grid-cols-3 gap-2">
                    <div class="bg-brand-soft-hoverBg rounded-lg p-2 text-center">
                        <p class="text-xl font-bold text-brand-soft-textMain">{{ $normal }}</p>
                        <p class="text-[9px] font-bold text-brand-soft-accent uppercase tracking-wider mt-0.5">Óptimo</p>
                    </div>
                    <div class="bg-orange-50/50 rounded-lg p-2 text-center border border-orange-50">
                        <p class="text-xl font-bold text-brand-soft-textMain">{{ $low }}</p>
                        <p class="text-[9px] font-bold text-brand-gold-500 uppercase tracking-wider mt-0.5">Bajo</p>
                    </div>
                    <div class="bg-red-50/50 rounded-lg p-2 text-center border border-red-50">
                        <p class="text-xl font-bold text-brand-soft-textMain">{{ $out }}</p>
                        <p class="text-[9px] font-bold text-brand-coral-500 uppercase tracking-wider mt-0.5">Agotado</p>
                    </div>
                </div>
            </div>

            <!-- Micro Reporte 2: Productos por Almacén -->
            <div class="bg-white rounded-xl shadow-sm border border-brand-soft-border p-5">
                <h3 class="text-sm font-bold text-brand-soft-textMain mb-4">Distribución por Almacén</h3>
                
                @php
                    // Agrupar stocks por nombre de almacén y contar líneas. No suma cantidad, solo cuenta líneas registradas.
                    $byWh = $stocks->groupBy(fn($s) => $s->warehouse->name)->map->count()->sortDesc()->take(4);
                    $maxWh = $byWh->max() ?: 1;
                @endphp

                <div class="space-y-3">
                    @forelse($byWh as $name => $count)
                        <div>
                            <div class="flex justify-between text-[11px] font-bold mb-1">
                                <span class="text-brand-soft-textMain">{{ $name }}</span>
                                <span class="text-brand-soft-textSec">{{ $count }} líneas</span>
                            </div>
                            <div class="w-full bg-brand-soft-hoverBg rounded-full h-1.5">
                                <div class="bg-brand-soft-accent h-1.5 rounded-full" style="width: {{ ($count / $maxWh) * 100 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-brand-soft-textSec text-center py-4">No hay datos suficientes</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
