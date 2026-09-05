<?php
use Livewire\Volt\Component;

new class extends Component
{
    // Lógica futura para bottom navigation
}; ?>

<div class="md:hidden fixed bottom-0 inset-x-0 bg-white border-t border-gray-200 z-40 pb-safe">
    <nav class="flex justify-around items-center h-16">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('dashboard') ? 'text-brand-green-700' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[10px] font-medium">Inicio</span>
        </a>

        <a href="{{ route('inventory.index') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('inventory.*') ? 'text-brand-green-700' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <span class="text-[10px] font-medium">Inventario</span>
        </a>

        <!-- Apps Launcher Mobile -->
        <button type="button" @click="showAppLauncher = true" class="flex flex-col items-center justify-center w-full h-full space-y-1 text-gray-500 hover:text-brand-green-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="text-[10px] font-medium">Apps</span>
        </button>

        <a href="{{ route('profile') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('profile') ? 'text-brand-green-700' : 'text-gray-500 hover:text-gray-900' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-[10px] font-medium">Perfil</span>
        </a>
    </nav>
</div>
