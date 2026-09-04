<div class="flex flex-col space-y-4">
    <div class="flex items-center space-x-2">
        <!-- SVG icon for inventory (box/package) -->
        <svg class="w-8 h-8 text-brand-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
        </svg>
        <h2 class="font-display font-bold text-2xl text-brand-navy-900 leading-tight">
            INVENTARIO
        </h2>
    </div>

    <!-- Navigation links -->
    <nav class="flex flex-wrap gap-x-6 gap-y-2 text-sm font-medium border-b border-gray-200 pb-2">
        <a href="{{ route('inventory.index') }}" wire:navigate 
           class="pb-2 border-b-2 transition-colors duration-200 {{ request()->routeIs('inventory.index') ? 'border-brand-green-700 text-brand-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Existencias & Resumen
        </a>
        <a href="{{ route('inventory.movements') }}" wire:navigate 
           class="pb-2 border-b-2 transition-colors duration-200 {{ request()->routeIs('inventory.movements') ? 'border-brand-green-700 text-brand-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Movimientos
        </a>
        <a href="{{ route('inventory.kardex') }}" wire:navigate 
           class="pb-2 border-b-2 transition-colors duration-200 {{ request()->routeIs('inventory.kardex') ? 'border-brand-green-700 text-brand-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Kardex
        </a>
        <a href="{{ route('inventory.adjustments') }}" wire:navigate 
           class="pb-2 border-b-2 transition-colors duration-200 {{ request()->routeIs('inventory.adjustments') ? 'border-brand-green-700 text-brand-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Ajustes
        </a>
        <a href="{{ route('inventory.transfers') }}" wire:navigate 
           class="pb-2 border-b-2 transition-colors duration-200 {{ request()->routeIs('inventory.transfers') ? 'border-brand-green-700 text-brand-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Transferencias
        </a>
        <a href="{{ route('inventory.warehouses') }}" wire:navigate 
           class="pb-2 border-b-2 transition-colors duration-200 {{ request()->routeIs('inventory.warehouses') ? 'border-brand-green-700 text-brand-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Almacenes
        </a>
    </nav>
</div>
