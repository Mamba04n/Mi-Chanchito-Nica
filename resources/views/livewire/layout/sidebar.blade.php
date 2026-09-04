<?php
use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    // Lógica futura para el menú lateral
}; ?>

<aside class="flex flex-col w-64 h-screen bg-brand-navy-900 border-r border-gray-800 transition-all duration-300">
    <!-- Logo Brand -->
    <div class="h-16 flex items-center justify-center border-b border-gray-800">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
            <x-application-logo class="h-8 w-auto text-brand-green-500" />
            <span class="text-white font-display font-bold text-xl tracking-wide">Chanchito</span>
        </a>
    </div>

    <!-- Global Navigation -->
    <div class="flex-1 overflow-y-auto py-6 flex flex-col gap-1 px-3">
        
        <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">General</p>

        <a href="{{ route('dashboard') }}" wire:navigate 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('dashboard') ? 'bg-brand-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>

        <div class="mt-4 mb-2">
            <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Módulos</p>
        </div>

        <a href="{{ route('inventory.index') }}" wire:navigate 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
           {{ request()->routeIs('inventory.*') ? 'bg-brand-green-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Inventario
        </a>

        <!-- Placeholder for future modules -->
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-500 hover:text-gray-300 cursor-not-allowed">
            <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Facturación <span class="ml-auto text-[10px] bg-gray-800 px-2 py-0.5 rounded-full">Próximamente</span>
        </a>
    </div>

    <!-- User & Settings (Bottom) -->
    <div class="p-4 border-t border-gray-800">
        <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 transition-colors">
            <div class="w-8 h-8 rounded-full bg-brand-green-700 flex items-center justify-center text-white font-bold">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="truncate text-white">{{ auth()->user()->name ?? 'Usuario' }}</p>
                <p class="truncate text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </a>
    </div>
</aside>
