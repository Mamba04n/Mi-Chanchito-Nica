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
                <a href="{{ route('dashboard') }}" wire:navigate>
                    <x-application-logo class="h-8 w-auto" />
                </a>
                <span class="text-brand-soft-textMain font-display font-bold text-lg tracking-wide mt-1">Chanchito</span>
            </div>
            <div class="flex items-center gap-4">
                <button @click="showAppLauncher = true" class="text-brand-soft-textSec hover:text-brand-soft-textMain transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </button>
            </div>
        </header>

        <!-- Desktop Sidebar (Hidden on mobile) -->
        <div class="hidden md:block h-full shrink-0">
            <livewire:layout.sidebar />
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            
            <!-- Desktop Topbar (Hidden on mobile) -->
            <div class="hidden md:block shrink-0">
                <livewire:layout.navigation />
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto pb-20 md:pb-0 relative">
                <!-- Optional Module Header -->
                @if (isset($header))
                    <header class="bg-white border-b border-brand-soft-border sticky top-0 z-20">
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

        <!-- Mobile Bottom Navigation -->
        <livewire:layout.bottom-navigation />
        
        <!-- Global App Launcher Modal -->
        <livewire:layout.app-launcher />

        <!-- Floating AI Assistant Mascot -->
        <div class="fixed bottom-20 md:bottom-8 right-6 md:right-8 z-40 flex flex-col items-end" x-data="{ open: false, greeting: true }" x-init="setTimeout(() => greeting = false, 5000)">
            <style>
                @keyframes float-ai {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-8px); }
                }
                .anim-float-ai { animation: float-ai 3s infinite ease-in-out; }
            </style>
            
            <!-- Speech Bubble -->
            <div x-show="greeting || open" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-90"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-90"
                 class="mb-4 bg-white p-4 rounded-2xl shadow-xl border border-brand-soft-border max-w-[280px] relative origin-bottom-right"
                 style="display: none;">
                
                <button @click="open = false; greeting = false" class="absolute top-2 right-2 p-1 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <div class="flex items-start gap-3 mt-1">
                    <div class="mt-1.5 shrink-0 w-2 h-2 rounded-full bg-brand-green-500 animate-pulse"></div>
                    <div>
                        <p class="text-[13px] font-bold text-brand-navy-900 mb-1 font-display tracking-wide">Tu Asistente IA</p>
                        <p class="text-[13px] text-brand-soft-textSec leading-snug">¡Hola! Soy Chanchito. Estoy aquí para ayudarte a analizar tus finanzas y configuraciones.</p>
                    </div>
                </div>

                <!-- Small triangle pointer -->
                <div class="absolute -bottom-2 right-7 w-4 h-4 bg-white border-b border-r border-brand-soft-border transform rotate-45"></div>
            </div>

            <!-- Mascot Button -->
            <button @click="open = !open; greeting = false" class="relative group outline-none focus:outline-none">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white shadow-lg border-2 border-brand-green-200 flex items-center justify-center anim-float-ai group-hover:border-brand-green-400 group-hover:shadow-xl transition-all p-1 overflow-hidden">
                    <img src="{{ asset('images/ai-pig.png') }}" alt="Asistente de IA" class="w-full h-full object-cover rounded-full" />
                </div>
                <!-- Status dot -->
                <div class="absolute bottom-1 right-1 w-4 h-4 md:w-5 md:h-5 bg-brand-green-500 border-2 border-white rounded-full shadow-sm"></div>
            </button>
        </div>
    </body>
</html>
