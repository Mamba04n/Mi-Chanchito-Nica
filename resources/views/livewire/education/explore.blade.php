<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-display text-brand-navy-900 mb-2">Explorar</h1>
            <p class="text-brand-soft-textSec text-lg">Catálogo por niveles y competencias</p>
        </div>
        <a href="{{ route('education.index') }}" class="text-brand-green-700 font-semibold hover:underline">Volver</a>
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