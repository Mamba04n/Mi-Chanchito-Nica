<div class="mb-6">
    <!-- Breadcrumb & Title -->
    <div class="mb-4">
        <nav class="flex text-xs text-gray-500 mb-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="#" class="hover:text-gray-900">Módulos</a></li>
                <li><span class="mx-1 text-gray-400">></span></li>
                <li aria-current="page"><span class="font-medium text-brand-green-700">Inventario</span></li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Control de Existencias</h1>
    </div>

    <!-- Navegación Contextual (Pill tabs) -->
    <nav class="flex space-x-2 overflow-x-auto hide-scrollbar" aria-label="Navegación del módulo">
        
        <a href="{{ route('inventory.index') }}" wire:navigate
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap
           {{ request()->routeIs('inventory.index') 
                ? 'bg-white text-gray-900 shadow-sm border border-gray-200' 
                : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
           Resumen
        </a>

        <a href="{{ route('inventory.movements') }}" wire:navigate
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap
           {{ request()->routeIs('inventory.movements') 
                ? 'bg-white text-gray-900 shadow-sm border border-gray-200' 
                : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
           Movimientos
        </a>

        <a href="{{ route('inventory.kardex') }}" wire:navigate
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap
           {{ request()->routeIs('inventory.kardex') 
                ? 'bg-white text-gray-900 shadow-sm border border-gray-200' 
                : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
           Kardex
        </a>

        <!-- Dropdown para Operaciones -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" 
                class="flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap
                {{ request()->routeIs('inventory.adjustments') || request()->routeIs('inventory.transfers') 
                    ? 'bg-white text-gray-900 shadow-sm border border-gray-200' 
                    : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                Operaciones
                <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 py-1">
                <a href="{{ route('inventory.adjustments') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-green-700">Ajustes de Stock</a>
                <a href="{{ route('inventory.transfers') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-green-700">Transferencias</a>
            </div>
        </div>

        <a href="{{ route('inventory.warehouses') }}" wire:navigate
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap
           {{ request()->routeIs('inventory.warehouses') 
                ? 'bg-white text-gray-900 shadow-sm border border-gray-200' 
                : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
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
