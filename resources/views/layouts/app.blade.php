<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Mi Chanchito Nica') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=open-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-brand-soft-bg text-brand-soft-textMain h-screen overflow-hidden flex flex-col md:flex-row" x-data="{ showAppLauncher: false }">
        
        <!-- Mobile Header (Visible only on mobile) -->
        <header class="md:hidden flex items-center justify-between px-4 h-16 bg-white border-b border-brand-soft-border shrink-0 z-30 relative">
            <div class="flex items-center gap-3">
                <x-application-logo class="h-8 w-auto" />
            </div>
            <div class="flex items-center gap-4">
                <button class="text-brand-soft-textSec hover:text-brand-soft-textMain relative transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-brand-coral-500 ring-2 ring-white"></span>
                </button>
            </div>
        </header>

        <!-- App Launcher Modal (Mobile & Desktop) -->
        <div x-show="showAppLauncher" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="showAppLauncher" x-transition.opacity class="absolute inset-0 bg-brand-soft-textMain/30 backdrop-blur-sm transition-opacity" @click="showAppLauncher = false"></div>
                
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="showAppLauncher" 
                        x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500" 
                        x-transition:enter-start="translate-x-full" 
                        x-transition:enter-end="translate-x-0" 
                        x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500" 
                        x-transition:leave-start="translate-x-0" 
                        x-transition:leave-end="translate-x-full" 
                        class="pointer-events-auto w-screen max-w-sm">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl rounded-l-3xl">
                            <div class="px-6 py-6 border-b border-brand-soft-border flex justify-between items-center bg-brand-soft-bg">
                                <h2 class="text-lg font-bold font-display text-brand-soft-textMain" id="slide-over-title">Aplicaciones</h2>
                                <button type="button" class="rounded-full bg-white p-2 text-brand-soft-textSec hover:text-brand-soft-textMain shadow-sm border border-brand-soft-border focus:outline-none transition-colors" @click="showAppLauncher = false">
                                    <span class="sr-only">Cerrar panel</span>
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                <div class="grid grid-cols-3 gap-4">
                                    <a href="{{ route('dashboard') }}" wire:navigate @click="showAppLauncher = false" class="flex flex-col items-center gap-2 p-3 rounded-2xl hover:bg-brand-soft-hoverBg transition-colors group">
                                        <div class="w-14 h-14 bg-brand-soft-bg text-brand-soft-textSec rounded-2xl flex items-center justify-center group-hover:bg-white group-hover:text-brand-soft-textMain transition-colors shadow-sm border border-transparent group-hover:border-brand-soft-border">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-brand-soft-textSec group-hover:text-brand-soft-textMain transition-colors text-center">Dashboard</span>
                                    </a>
                                    
                                    <a href="{{ route('inventory.index') }}" wire:navigate @click="showAppLauncher = false" class="flex flex-col items-center gap-2 p-3 rounded-2xl hover:bg-brand-soft-activeBg transition-colors group">
                                        <div class="w-14 h-14 bg-brand-soft-activeBg text-brand-soft-activeText rounded-2xl flex items-center justify-center shadow-sm border border-brand-soft-border group-hover:scale-105 transition-transform">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <span class="text-xs font-bold text-brand-soft-activeText text-center">Inventario</span>
                                    </a>

                                    <!-- Placeholder locked apps -->
                                    <div class="flex flex-col items-center gap-2 p-3 rounded-2xl opacity-50 cursor-not-allowed">
                                        <div class="w-14 h-14 bg-brand-soft-bg text-brand-soft-textSec rounded-2xl flex items-center justify-center border border-brand-soft-border">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-brand-soft-textSec text-center">Facturación</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-2 p-3 rounded-2xl opacity-50 cursor-not-allowed">
                                        <div class="w-14 h-14 bg-brand-soft-bg text-brand-soft-textSec rounded-2xl flex items-center justify-center border border-brand-soft-border">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-500 text-center">RRHH</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar (Hidden on mobile) -->
        <div class="hidden md:block h-full">
            <livewire:layout.sidebar />
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            
            <!-- Desktop Topbar (Hidden on mobile) -->
            <div class="hidden md:block">
                <livewire:layout.navigation />
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto pb-20 md:pb-0 relative">
                <!-- Optional Module Header -->
                @if (isset($header))
                    <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
                        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile Floating Action Button (Inventory Operations) -->
        @if(request()->routeIs('inventory.*'))
        <div class="md:hidden fixed bottom-20 right-4 z-40" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="w-14 h-14 bg-brand-green-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-brand-green-700/30 hover:bg-brand-green-500 hover:scale-105 transition-all focus:outline-none">
                <svg x-show="!open" class="w-6 h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <svg x-show="open" style="display: none;" class="w-6 h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div x-show="open" style="display: none;" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="absolute bottom-16 right-0 mb-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 flex flex-col gap-1 z-50">
                <a href="{{ route('inventory.adjustments') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-green-700">
                    <span class="w-6 h-6 rounded bg-gray-100 flex items-center justify-center text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></span>
                    Ajuste Manual
                </a>
                <a href="{{ route('inventory.transfers') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-green-700">
                    <span class="w-6 h-6 rounded bg-brand-green-50 flex items-center justify-center text-brand-green-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg></span>
                    Transferencia
                </a>
            </div>
        </div>
        @endif

        <!-- Mobile Bottom Navigation -->
        <livewire:layout.bottom-navigation />
    </body>
</html>
