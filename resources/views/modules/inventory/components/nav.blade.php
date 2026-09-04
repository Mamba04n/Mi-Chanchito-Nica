<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <!-- Título del Módulo (Gliker) -->
    <div class="flex items-center gap-3">
        <div class="p-2 bg-brand-green-700 text-white rounded-lg shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-display font-bold text-brand-navy-900 tracking-tight">Inventario</h1>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Control de Existencias</p>
        </div>
    </div>

    <!-- Navegación Contextual (Odoo-like) -->
    <nav class="flex space-x-1 overflow-x-auto pb-2 md:pb-0 hide-scrollbar" aria-label="Navegación del módulo">
        
        <a href="{{ route('inventory.index') }}" wire:navigate
           class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap
           {{ request()->routeIs('inventory.index') 
                ? 'bg-brand-green-50 text-brand-green-700' 
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
           <svg class="w-4 h-4 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
           Resumen
        </a>

        <a href="{{ route('inventory.movements') }}" wire:navigate
           class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap
           {{ request()->routeIs('inventory.movements') 
                ? 'bg-brand-green-50 text-brand-green-700' 
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
           <svg class="w-4 h-4 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
           Movimientos
        </a>

        <a href="{{ route('inventory.kardex') }}" wire:navigate
           class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap
           {{ request()->routeIs('inventory.kardex') 
                ? 'bg-brand-green-50 text-brand-green-700' 
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
           <svg class="w-4 h-4 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
           Kardex
        </a>

        <!-- Dropdown para Operaciones -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" 
                class="flex items-center gap-1 px-3 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap
                {{ request()->routeIs('inventory.adjustments') || request()->routeIs('inventory.transfers') 
                    ? 'bg-brand-green-50 text-brand-green-700' 
                    : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Operaciones
                <svg class="w-4 h-4 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 z-50">
                <a href="{{ route('inventory.adjustments') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-green-700">Ajustes de Stock</a>
                <a href="{{ route('inventory.transfers') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-green-700">Transferencias</a>
            </div>
        </div>

        <a href="{{ route('inventory.warehouses') }}" wire:navigate
           class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap
           {{ request()->routeIs('inventory.warehouses') 
                ? 'bg-brand-green-50 text-brand-green-700' 
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
           <svg class="w-4 h-4 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
           Almacenes
        </a>
    </nav>
</div>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
