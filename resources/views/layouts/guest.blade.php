<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Mi Chanchito Nica') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=open-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @font-face {
                font-family: 'Gliker';
                src: local('Gliker');
                font-weight: 400;
                font-style: normal;
                font-display: swap;
            }
            .font-display { font-family: 'Gliker', 'Nunito', 'Open Sans', sans-serif; }
        </style>
    </head>
    <body class="font-sans text-brand-navy-900 antialiased">
        {{-- Full-screen split layout --}}
        <div class="min-h-screen flex flex-col lg:flex-row">

            {{-- LEFT PANEL — Branding --}}
            <div class="relative lg:w-[45%] flex flex-col items-center justify-center px-8 py-10 lg:py-16 overflow-hidden"
                 style="background: linear-gradient(160deg, #f4f9f5 0%, #eaf3eb 40%, #f0f5ee 100%);">

                {{-- Decorative organic shapes --}}
                <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden" aria-hidden="true">
                    <svg class="absolute -top-8 -left-8 w-48 h-48 opacity-[0.12]" viewBox="0 0 200 200" fill="none">
                        <path d="M0 100C0 44.8 44.8 0 100 0s100 44.8 100 100-44.8 100-100 100S0 155.2 0 100z" fill="#6FA65E"/>
                    </svg>
                    <svg class="absolute -bottom-6 -right-6 w-56 h-40 opacity-[0.10]" viewBox="0 0 300 200" fill="none">
                        <path d="M0 140c50-30 100-60 150-40s100 50 150 20V200H0z" fill="#1D6B46"/>
                    </svg>
                    <svg class="absolute bottom-6 left-6 w-16 h-16 opacity-[0.18]" viewBox="0 0 60 60" fill="none">
                        <path d="M10 50C10 25 25 10 50 10c0 25-15 40-40 40z" fill="#6FA65E"/>
                        <path d="M10 50C25 35 35 25 50 10" stroke="#1D6B46" stroke-width="1.5" fill="none"/>
                    </svg>
                </div>

                {{-- Top-left decorative text --}}
                <p class="absolute top-6 left-6 text-sm font-display italic text-brand-green-700 opacity-60 hidden lg:block leading-tight">
                    Pequeños<br>hábitos,<br>grandes<br>resultados
                </p>

                {{-- Logo --}}
                <div class="relative z-10 flex flex-col items-center">
                    <img src="{{ asset('images/brand/mi-chanchito-logo.png') }}"
                         alt="Mi Chanchito Nica"
                         class="w-28 h-28 sm:w-36 sm:h-36 lg:w-48 lg:h-48 object-contain mb-4 lg:mb-6">

                    <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl text-brand-navy-900 text-center leading-tight tracking-tight">
                        Mi Chanchito<br>
                        <span class="text-brand-green-700">Nica</span>
                    </h1>

                    <p class="mt-3 text-xs sm:text-sm tracking-[0.25em] uppercase text-brand-soft-textSec font-semibold text-center">
                        Aprende &bull; Ahorra &bull; Logra tus metas
                    </p>

                    <p class="mt-5 lg:mt-8 font-display italic text-brand-green-700 text-base lg:text-lg opacity-80 text-center">
                        Un mejor futuro<br>comienza hoy
                    </p>
                </div>
            </div>

            {{-- RIGHT PANEL — Form --}}
            <div class="flex-1 flex flex-col items-center justify-center px-6 sm:px-10 lg:px-14 py-10 lg:py-16 bg-white relative">
                {{-- Top-right decorative text --}}
                <p class="absolute top-5 right-6 text-xs sm:text-sm font-display italic text-brand-green-700 opacity-50 text-right hidden lg:block leading-tight">
                    Finanzas más simples<br>para una vida mejor
                </p>

                <div class="w-full max-w-sm lg:max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
