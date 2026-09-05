<div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold font-display text-brand-navy-900 tracking-tight">Bienvenido, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-brand-soft-textSec mt-1">Resumen financiero de {{ app(\App\Context\CompanyContext::class)->getCompany()?->name }}</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $activeModulesCount > 0 ? 'bg-brand-green-100 text-brand-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                <span class="w-2 h-2 rounded-full {{ $activeModulesCount > 0 ? 'bg-brand-green-500' : 'bg-yellow-500' }} mr-2"></span>
                {{ $activeModulesCount > 0 ? 'Operativo' : 'Configuración Pendiente' }}
            </span>
        </div>
    </div>

    @if($activeModulesCount === 0)
    <!-- Empty State: No Modules Installed -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-brand-soft-border text-center flex flex-col items-center justify-center min-h-[50vh]">
        
        <!-- Animated Pig Scene -->
        <div class="relative w-full h-56 bg-gradient-to-b from-[#F0FDF4] to-white flex justify-center items-end pb-8 border-b border-brand-soft-border">
            <style>
                @keyframes snore {
                    0% { opacity: 0; transform: translate(0, 0) scale(0.5); }
                    50% { opacity: 1; transform: translate(20px, -20px) scale(1); }
                    100% { opacity: 0; transform: translate(40px, -40px) scale(1.5); }
                }
                @keyframes breathe {
                    0%, 100% { transform: scaleY(1); }
                    50% { transform: scaleY(1.08) translateY(-2px); }
                }
                .anim-z1 { animation: snore 4s infinite linear; }
                .anim-z2 { animation: snore 4s infinite linear 1.3s; }
                .anim-z3 { animation: snore 4s infinite linear 2.6s; }
                .anim-breathe { animation: breathe 4s infinite ease-in-out; transform-origin: bottom center; }
            </style>

            <div class="relative">
                <!-- Zzz's -->
                <div class="absolute -top-16 -right-12 text-brand-navy-300 font-gliker font-bold text-2xl">
                    <span class="absolute anim-z1">z</span>
                    <span class="absolute anim-z2">Z</span>
                    <span class="absolute anim-z3">z</span>
                </div>
                
                <!-- Sleeping Pig SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 80" class="w-56 h-28 drop-shadow-sm">
                    <!-- Tail -->
                    <path d="M135 60 Q 150 50, 145 65 Q 140 75, 150 70" fill="none" stroke="#F472B6" stroke-width="4" stroke-linecap="round"/>
                    
                    <!-- Body (animated breathe) -->
                    <g class="anim-breathe">
                        <ellipse cx="90" cy="55" rx="50" ry="30" fill="#FBCFE8" />
                        <!-- Folded Legs -->
                        <path d="M60 75 Q 70 85, 80 80" fill="none" stroke="#F472B6" stroke-width="8" stroke-linecap="round"/>
                        <path d="M110 75 Q 120 85, 130 80" fill="none" stroke="#F472B6" stroke-width="8" stroke-linecap="round"/>
                    </g>
                    
                    <!-- Head -->
                    <circle cx="45" cy="55" r="28" fill="#FBCFE8" />
                    
                    <!-- Closed Eye -->
                    <path d="M35 48 Q 45 55, 55 48" fill="none" stroke="#111827" stroke-width="3" stroke-linecap="round"/>
                    
                    <!-- Snout -->
                    <ellipse cx="25" cy="65" rx="12" ry="16" fill="#F472B6" transform="rotate(-30 25 65)" />
                    <circle cx="22" cy="62" r="2" fill="#BE185D" />
                    <circle cx="29" cy="67" r="2" fill="#BE185D" />
                    
                    <!-- Ear (flopped over) -->
                    <path d="M50 30 Q 70 20, 65 45 Z" fill="#F472B6" />
                </svg>
            </div>
        </div>

        <div class="p-10">
            <h2 class="text-3xl font-display font-bold text-brand-navy-900 mb-4">El chanchito está durmiendo...</h2>
        <p class="text-lg text-brand-soft-textSec max-w-xl mx-auto mb-8">
            Aún no has activado ninguna aplicación en tu empresa. Selecciona un <strong>Pack Recomendado</strong> o arma tu ecosistema a la medida para comenzar a ver información.
        </p>
        <a href="{{ route('settings.modules') }}" wire:navigate class="inline-flex items-center px-8 py-4 bg-brand-green-700 text-white font-bold text-lg rounded-2xl shadow-md hover:bg-brand-green-800 hover:shadow-lg transition-all hover:-translate-y-1">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            Configurar Aplicaciones
        </a>
    </div>
    @else
    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-brand-soft-textSec">Total Facturado</h3>
                <div class="w-10 h-10 rounded-xl bg-brand-soft-bg flex items-center justify-center text-brand-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-2xl font-bold text-brand-navy-900">C$ 0.00</p>
                <p class="text-xs text-brand-soft-textSec mt-1">Este mes</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-brand-soft-textSec">Cuentas por Cobrar</h3>
                <div class="w-10 h-10 rounded-xl bg-brand-soft-bg flex items-center justify-center text-brand-coral-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-2xl font-bold text-brand-navy-900">C$ 0.00</p>
                <p class="text-xs text-brand-coral-500 mt-1 flex items-center gap-1">
                    0 facturas vencidas
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-brand-soft-textSec">Cuentas por Pagar</h3>
                <div class="w-10 h-10 rounded-xl bg-brand-soft-bg flex items-center justify-center text-brand-coral-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-2xl font-bold text-brand-navy-900">C$ 0.00</p>
                <p class="text-xs text-brand-soft-textSec mt-1 flex items-center gap-1">
                    Próximos vencimientos
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-brand-soft-textSec">Caja Mínima</h3>
                <div class="w-10 h-10 rounded-xl bg-brand-soft-bg flex items-center justify-center text-brand-navy-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
            </div>
            <div class="mt-auto">
                <p class="text-2xl font-bold text-brand-navy-900">C$ 0.00</p>
                <p class="text-xs text-brand-soft-textSec mt-1 flex items-center gap-1">
                    Tesorería Global
                </p>
            </div>
        </div>
    </div>

    <!-- Charts / Alerts section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-brand-soft-border p-6 min-h-[300px] flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-brand-soft-bg rounded-full flex items-center justify-center mb-4 text-brand-soft-textSec">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <h4 class="text-lg font-bold text-brand-navy-900">Sin suficientes datos</h4>
            <p class="text-sm text-brand-soft-textSec mt-2 max-w-sm">Registra ventas, compras y movimientos de inventario para ver los gráficos operativos.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-6">
            <h4 class="text-base font-bold text-brand-navy-900 mb-4 border-b border-brand-soft-border pb-3">Alertas Recientes</h4>
            <div class="flex flex-col gap-4">
                <div class="flex gap-3">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-brand-coral-50 flex items-center justify-center text-brand-coral-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-brand-navy-900">Módulo Tesorería Inactivo</p>
                        <p class="text-[11px] text-brand-soft-textSec mt-0.5">Ve a ajustes para activarlo.</p>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-brand-green-50 flex items-center justify-center text-brand-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-brand-navy-900">Bienvenido al sistema</p>
                        <p class="text-[11px] text-brand-soft-textSec mt-0.5">Tu cuenta ha sido creada exitosamente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
