<div class="flex items-center justify-between w-full">
    <!-- Breadcrumb & Title -->
    <div class="flex items-center gap-3">
        <h1 class="text-xl md:text-2xl font-bold text-brand-soft-textMain tracking-tight font-display hidden md:block">Inventario</h1>
        
        <div class="hidden md:block w-px h-6 bg-gray-200 mx-1"></div>
        
        <!-- Dropdown de Navegación del Módulo -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" 
                class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors">
                @if(request()->routeIs('inventory.index'))
                    Resumen de Stock
                @elseif(request()->routeIs('inventory.movements'))
                    Historial de Movimientos
                @elseif(request()->routeIs('inventory.kardex'))
                    Kardex Contable
                @elseif(request()->routeIs('inventory.warehouses'))
                    Gestión de Almacenes
                @elseif(request()->routeIs('inventory.adjustments'))
                    Ajustes
                @elseif(request()->routeIs('inventory.transfers'))
                    Transferencias
                @else
                    Inventario
                @endif
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" style="display: none;" 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-brand-soft-border z-50 py-1 font-medium">
                
                <div class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Vistas</div>
                <a href="{{ route('inventory.index') }}" wire:navigate class="block px-4 py-2 text-sm {{ request()->routeIs('inventory.index') ? 'text-brand-green-700 bg-green-50' : 'text-gray-700 hover:bg-brand-soft-hoverBg' }}">Resumen y Existencias</a>
                <a href="{{ route('inventory.movements') }}" wire:navigate class="block px-4 py-2 text-sm {{ request()->routeIs('inventory.movements') ? 'text-brand-green-700 bg-green-50' : 'text-gray-700 hover:bg-brand-soft-hoverBg' }}">Movimientos</a>
                <a href="{{ route('inventory.kardex') }}" wire:navigate class="block px-4 py-2 text-sm {{ request()->routeIs('inventory.kardex') ? 'text-brand-green-700 bg-green-50' : 'text-gray-700 hover:bg-brand-soft-hoverBg' }}">Kardex</a>
                <a href="{{ route('inventory.warehouses') }}" wire:navigate class="block px-4 py-2 text-sm {{ request()->routeIs('inventory.warehouses') ? 'text-brand-green-700 bg-green-50' : 'text-gray-700 hover:bg-brand-soft-hoverBg' }}">Almacenes</a>
                
                <div class="border-t border-brand-soft-border my-1"></div>
                <div class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Operaciones</div>
                <a href="{{ route('inventory.adjustments') }}" wire:navigate class="block px-4 py-2 text-sm {{ request()->routeIs('inventory.adjustments') ? 'text-brand-green-700 bg-green-50' : 'text-gray-700 hover:bg-brand-soft-hoverBg' }}">Ajuste Manual</a>
                <a href="{{ route('inventory.transfers') }}" wire:navigate class="block px-4 py-2 text-sm {{ request()->routeIs('inventory.transfers') ? 'text-brand-green-700 bg-green-50' : 'text-gray-700 hover:bg-brand-soft-hoverBg' }}">Transferencia</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions (Desktop only) -->
    <div class="hidden md:flex items-center gap-2">
        <a href="{{ route('inventory.adjustments') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-brand-soft-hoverBg transition-colors shadow-sm">
            <svg class="w-4 h-4 text-brand-soft-textSec" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Ajustar
        </a>
        <a href="{{ route('inventory.transfers') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-navy-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            Transferir
        </a>
    </div>
</div>
