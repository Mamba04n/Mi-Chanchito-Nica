<x-app-layout>
    <div class="max-w-6xl mx-auto space-y-8 pb-12">
        <!-- Header -->
        <div class="mb-8 mt-4">
            <h1 class="font-gliker text-4xl text-brand-navy-900 leading-tight">Perfil de Usuario</h1>
            <p class="font-sans text-brand-soft-textSec text-lg mt-2">Administra tu información personal, seguridad y acceso al ecosistema.</p>
        </div>

        <!-- Fila 1: Resumen de Usuario -->
        <div class="bg-gradient-to-br from-brand-navy-900 to-brand-navy-800 rounded-3xl p-8 shadow-sm border border-brand-navy-800 text-white flex flex-col md:flex-row items-center gap-6">
            <div class="w-24 h-24 rounded-full bg-brand-green-500 border-4 border-brand-navy-900 shadow-md flex items-center justify-center text-4xl font-gliker text-white shrink-0 uppercase">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-3xl font-display font-bold">{{ auth()->user()->name }}</h2>
                <p class="text-brand-green-100 flex items-center justify-center md:justify-start gap-2 mt-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    {{ auth()->user()->email }}
                </p>
                <div class="mt-4 flex flex-wrap gap-3 justify-center md:justify-start">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-green-700/50 text-white border border-brand-green-500/30">
                        Usuario Activo
                    </span>
                    @php $company = app(\App\Context\CompanyContext::class)->getCompany(); @endphp
                    @if($company)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-white border border-white/20">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ $company->name }}
                    </span>
                    @endif
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-brand-gold-500 border border-white/20">
                        Miembro desde {{ auth()->user()->created_at->translatedFormat('F Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Fila 2: Información Personal y Seguridad -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Info Personal -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-brand-soft-border">
                <livewire:profile.update-profile-information-form />
            </div>

            <!-- Seguridad -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-brand-soft-border">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <!-- Fila 3: Zona de Peligro -->
        <div class="bg-[#FFF5F5] rounded-3xl p-8 shadow-sm border border-brand-coral-200 mt-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-coral-500/5 rounded-full -mr-16 -mt-16"></div>
            <livewire:profile.delete-user-form />
        </div>
    </div>
</x-app-layout>
