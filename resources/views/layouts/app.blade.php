<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F8F9FA] text-brand-navy-900 overflow-hidden">
        <div class="flex h-screen w-full">
            <!-- Sidebar (Left) -->
            <livewire:layout.sidebar />

            <!-- Main Content (Right) -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                
                <!-- Top Navigation Bar -->
                <livewire:layout.navigation />

                <!-- Page Content Area -->
                <div class="flex-1 overflow-y-auto">
                    <!-- Optional Module Header -->
                    @if (isset($header))
                        <header class="bg-white border-b border-gray-200">
                            <div class="max-w-7xl mx-auto py-4 px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
