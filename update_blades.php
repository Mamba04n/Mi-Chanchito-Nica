<?php

$blades = [
    'resources/views/livewire/education/home.blade.php' => <<<'HTML'
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="mb-8">
        <h1 class="text-4xl font-display text-brand-navy-900 mb-2">Academia</h1>
        <p class="text-brand-soft-textSec text-lg">Tu dashboard de aprendizaje</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Dashboard Widgets / Quick Actions -->
        <a href="{{ route('education.explore') }}" class="block bg-neutral-white p-6 rounded-xl shadow-sm border border-brand-soft-border hover:shadow-md transition">
            <h3 class="font-display text-xl text-brand-green-700 mb-2">Explorar Catálogo</h3>
            <p class="text-neutral-charcoal text-sm">Descubre nuevos programas y niveles.</p>
        </a>

        <a href="{{ route('education.my-learning') }}" class="block bg-neutral-white p-6 rounded-xl shadow-sm border border-brand-soft-border hover:shadow-md transition">
            <h3 class="font-display text-xl text-brand-gold-500 mb-2">Mi Aprendizaje</h3>
            <p class="text-neutral-charcoal text-sm">Retoma donde te quedaste.</p>
        </a>
    </div>
</div>
HTML,

    'resources/views/livewire/education/explore.blade.php' => <<<'HTML'
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-display text-brand-navy-900 mb-2">Explorar</h1>
            <p class="text-brand-soft-textSec text-lg">Catálogo por niveles y competencias</p>
        </div>
        <a href="{{ route('education.home') }}" class="text-brand-green-700 font-semibold hover:underline">Volver</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Mock Program Card -->
        <div class="bg-neutral-white rounded-xl shadow-sm border border-brand-soft-border overflow-hidden">
            <div class="h-32 bg-brand-pink-300"></div>
            <div class="p-5">
                <span class="inline-block px-3 py-1 bg-brand-green-500 text-neutral-white text-xs font-bold rounded-full mb-3">Beginner</span>
                <h3 class="font-display text-lg text-brand-navy-900 mb-2">Finanzas Básicas</h3>
                <p class="text-neutral-charcoal text-sm mb-4">Duración: 30 mins</p>
                <a href="#" class="block w-full text-center bg-brand-green-700 text-neutral-white py-2 rounded-lg font-semibold hover:bg-brand-green-500 transition">Ver Programa</a>
            </div>
        </div>
    </div>
</div>
HTML,

    'resources/views/livewire/education/my-learning.blade.php' => <<<'HTML'
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-display text-brand-navy-900 mb-2">Mi Aprendizaje</h1>
            <p class="text-brand-soft-textSec text-lg">Programas iniciados</p>
        </div>
        <a href="{{ route('education.home') }}" class="text-brand-green-700 font-semibold hover:underline">Volver</a>
    </div>

    <div class="space-y-4">
        <!-- Mock Progress Card -->
        <div class="bg-neutral-white p-5 rounded-xl shadow-sm border border-brand-soft-border flex items-center justify-between">
            <div>
                <h3 class="font-display text-lg text-brand-navy-900">Gestión de Cuentas por Cobrar</h3>
                <p class="text-neutral-charcoal text-sm">Progreso: 50%</p>
            </div>
            <a href="#" class="bg-brand-green-700 text-neutral-white px-4 py-2 rounded-lg font-semibold hover:bg-brand-green-500 transition">Continuar</a>
        </div>
    </div>
</div>
HTML,

    'resources/views/livewire/education/program-detail.blade.php' => <<<'HTML'
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="mb-8">
        <a href="{{ route('education.explore') }}" class="text-brand-green-700 font-semibold hover:underline mb-4 inline-block">&larr; Volver al catálogo</a>
        <h1 class="text-4xl font-display text-brand-navy-900 mb-2">Gestión de Inventario</h1>
        <p class="text-brand-soft-textSec text-lg mb-4">Aprende a controlar tus existencias.</p>
        <div class="flex items-center space-x-2">
            <span class="px-3 py-1 bg-brand-coral-500 text-neutral-white text-xs font-bold rounded-full">Intermediate</span>
            <span class="text-neutral-charcoal text-sm">45 minutos</span>
        </div>
    </div>

    <div class="bg-neutral-white rounded-xl shadow-sm border border-brand-soft-border p-6">
        <h3 class="font-display text-xl text-brand-navy-900 mb-4">Unidades</h3>
        <ul class="space-y-3">
            <li class="flex items-center justify-between p-3 bg-brand-soft-bg rounded-lg">
                <span class="text-neutral-charcoal">1. Introducción al Inventario</span>
                <span class="text-brand-green-500 font-bold">&#10003;</span>
            </li>
            <li class="flex items-center justify-between p-3 bg-brand-soft-bg rounded-lg border-l-4 border-brand-gold-500">
                <span class="text-neutral-charcoal font-semibold">2. Métodos de Valuación</span>
                <a href="#" class="text-brand-green-700 text-sm font-bold hover:underline">Iniciar</a>
            </li>
        </ul>
    </div>
</div>
HTML,

    'resources/views/livewire/education/lesson-viewer.blade.php' => <<<'HTML'
<div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <a href="#" class="text-brand-green-700 font-semibold hover:underline mb-4 inline-block">&larr; Volver al programa</a>
        <h1 class="text-3xl font-display text-brand-navy-900">Métodos de Valuación</h1>
    </div>

    <div class="prose prose-brand max-w-none text-neutral-charcoal mb-8">
        <p>Contenido de la lección en markdown renderizado iría aquí.</p>
    </div>

    <div class="bg-brand-pink-300/20 p-4 rounded-xl mb-8 border border-brand-pink-300">
        <h4 class="font-bold text-brand-navy-900 mb-2">Fuentes de la lección</h4>
        <ul class="text-sm text-neutral-charcoal space-y-1">
            <li>Institución ABC - <a href="#" class="text-brand-green-700 underline">Enlace original</a></li>
        </ul>
    </div>

    <button class="w-full bg-brand-green-700 text-neutral-white py-3 rounded-xl font-bold hover:bg-brand-green-500 transition shadow-sm">
        Completar Lección
    </button>
</div>
HTML,

    'resources/views/livewire/education/assessment-viewer.blade.php' => <<<'HTML'
<div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-display text-brand-navy-900 mb-6">Evaluación Final</h1>
    
    <div class="bg-neutral-white rounded-xl shadow-sm border border-brand-soft-border p-6 mb-6">
        <p class="font-semibold text-neutral-charcoal mb-4">1. ¿Cuál es el principal objetivo del flujo de caja?</p>
        <div class="space-y-3">
            <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-brand-soft-bg">
                <input type="radio" name="q1" class="text-brand-green-700 focus:ring-brand-green-500">
                <span class="text-neutral-charcoal">Controlar entradas y salidas de efectivo</span>
            </label>
            <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-brand-soft-bg">
                <input type="radio" name="q1" class="text-brand-green-700 focus:ring-brand-green-500">
                <span class="text-neutral-charcoal">Aumentar las ventas</span>
            </label>
        </div>
    </div>

    <button class="w-full bg-brand-navy-900 text-neutral-white py-3 rounded-xl font-bold hover:bg-neutral-charcoal transition shadow-sm">
        Enviar Respuestas
    </button>
</div>
HTML,

    'resources/views/livewire/gamification/dashboard.blade.php' => <<<'HTML'
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <!-- Header Perfil -->
    <div class="bg-neutral-white rounded-2xl shadow-sm border border-brand-soft-border p-6 sm:p-8 mb-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between">
        <div class="flex items-center space-x-6 mb-4 sm:mb-0">
            <div class="w-24 h-24 rounded-full bg-brand-gold-500 flex items-center justify-center text-4xl font-display text-neutral-white shadow-md">
                N3
            </div>
            <div>
                <h1 class="text-3xl font-display text-brand-navy-900">Nivel 3</h1>
                <p class="text-brand-soft-textSec text-lg">340 XP Totales</p>
            </div>
        </div>
        <div class="w-full sm:w-1/3">
            <div class="flex justify-between text-sm text-neutral-charcoal mb-2 font-semibold">
                <span>Progreso a Nivel 4</span>
                <span>45%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-brand-green-500 h-3 rounded-full" style="width: 45%"></div>
            </div>
            <p class="text-xs text-brand-soft-textSec text-right mt-1">Faltan 110 XP</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Racha -->
        <div class="bg-brand-pink-300/20 p-6 rounded-2xl border border-brand-pink-300 text-center flex flex-col items-center justify-center">
            <div class="text-5xl mb-3">🔥</div>
            <h3 class="text-2xl font-display text-brand-navy-900">5 Días</h3>
            <p class="text-neutral-charcoal text-sm">Racha actual</p>
        </div>

        <!-- Logros Recientes -->
        <div class="md:col-span-2 bg-neutral-white p-6 rounded-2xl shadow-sm border border-brand-soft-border">
            <h3 class="text-xl font-display text-brand-navy-900 mb-4">Logros Recientes</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="text-center p-3 rounded-xl hover:bg-brand-soft-bg transition">
                    <div class="w-12 h-12 mx-auto bg-brand-gold-500 rounded-full flex items-center justify-center text-white text-xl mb-2 shadow-sm">⭐</div>
                    <p class="font-bold text-sm text-brand-navy-900">Primer Paso</p>
                    <p class="text-xs text-brand-soft-textSec">+20 XP</p>
                </div>
            </div>
        </div>

        <!-- Retos Activos -->
        <div class="md:col-span-3 bg-neutral-white p-6 rounded-2xl shadow-sm border border-brand-soft-border">
            <h3 class="text-xl font-display text-brand-navy-900 mb-4">Retos Activos</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-6 h-6 rounded-full border-2 border-brand-green-500 flex items-center justify-center text-brand-green-500">
                            <!-- Si progreso = 1/2, a medias. Aquí mock. -->
                            <div class="w-3 h-3 bg-brand-green-500 rounded-full opacity-50"></div>
                        </div>
                        <div>
                            <p class="font-bold text-brand-navy-900">Dos lecciones de CxC</p>
                            <p class="text-sm text-brand-soft-textSec">Completa 2 lecciones de cuentas por cobrar para ganar XP extra.</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 bg-brand-gold-500 text-neutral-white text-xs font-bold rounded-full mb-1">+50 XP</span>
                        <p class="text-xs text-neutral-charcoal">1/2 completado</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
HTML
];

foreach ($blades as $path => $content) {
    $fullPath = "c:/Users/Mamba/Desktop/Chanchito Nica/" . $path;
    file_put_contents($fullPath, $content);
    echo "Updated $path\n";
}
