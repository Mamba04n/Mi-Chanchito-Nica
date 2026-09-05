<div class="max-w-6xl mx-auto space-y-8 pb-12">
    <!-- Header -->
    <div class="text-center max-w-2xl mx-auto pt-8">
        <h1 class="text-3xl font-bold font-display text-brand-navy-900 mb-3">Configura tu Ecosistema</h1>
        <p class="text-brand-soft-textSec">
            Activa solamente las herramientas que tu negocio necesita en este momento. 
            Siempre podrás cambiarlas o escalarlas después sin perder información.
        </p>
    </div>

    @if (session()->has('error'))
    <div class="p-4 bg-brand-coral-50 border-l-4 border-brand-coral-500 rounded-r-xl shadow-sm flex gap-3">
        <svg class="w-6 h-6 text-brand-coral-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h4 class="text-sm font-bold text-brand-navy-900">No se pudo guardar la configuración</h4>
            <p class="text-sm text-brand-coral-600 mt-1">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Packs Recomendados -->
    <div>
        <h2 class="text-xl font-bold text-brand-navy-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-brand-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            Packs Recomendados
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($packs as $key => $pack)
            <div wire:click="selectPack('{{ $key }}')" 
                 class="relative p-6 bg-white rounded-3xl border-2 cursor-pointer transition-all duration-300 {{ $selectedPack === $key ? 'border-brand-green-500 shadow-md ring-4 ring-brand-green-50' : 'border-brand-soft-border shadow-sm hover:border-brand-green-300 hover:shadow' }}">
                
                @if($selectedPack === $key)
                <div class="absolute top-4 right-4 text-brand-green-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                @endif
                
                <span class="inline-block px-3 py-1 bg-brand-soft-bg text-brand-navy-900 text-[10px] font-bold uppercase tracking-widest rounded-full mb-4">{{ $pack['level'] }}</span>
                <h3 class="text-lg font-bold text-brand-navy-900 mb-2">{{ $pack['name'] }}</h3>
                <p class="text-sm text-brand-soft-textSec mb-6 min-h-[60px]">{{ $pack['desc'] }}</p>
                
                <div class="flex flex-wrap gap-1">
                    @foreach($pack['modules'] as $modKey)
                        @php
                            $modName = collect($availableModules)->firstWhere('key', $modKey)['name'] ?? $modKey;
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                            {{ $modName }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Personalizado Card -->
            <div wire:click="$set('selectedPack', 'personalizado')" 
                 class="relative p-6 bg-white rounded-3xl border-2 cursor-pointer transition-all duration-300 {{ $selectedPack === 'personalizado' ? 'border-brand-green-500 shadow-md ring-4 ring-brand-green-50' : 'border-brand-soft-border shadow-sm hover:border-brand-green-300 hover:shadow' }}">
                @if($selectedPack === 'personalizado')
                <div class="absolute top-4 right-4 text-brand-green-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                @endif
                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-widest rounded-full mb-4">A tu Medida</span>
                <h3 class="text-lg font-bold text-brand-navy-900 mb-2">Personalizado</h3>
                <p class="text-sm text-brand-soft-textSec mb-6 min-h-[60px]">Arma tu propio ecosistema seleccionando únicamente las piezas que necesitas.</p>
                <div class="text-xs text-brand-green-600 font-semibold flex items-center">
                    Seleccionar manualmente
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Selector de Módulos (Detallado) -->
    <div class="bg-white rounded-3xl shadow-sm border border-brand-soft-border overflow-hidden">
        <div class="p-6 border-b border-brand-soft-border bg-gray-50 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-brand-navy-900">Catálogo de Módulos</h2>
                <p class="text-sm text-brand-soft-textSec">Selecciona o ajusta las aplicaciones de tu espacio.</p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-display font-bold text-brand-green-700">{{ count($activeModuleKeys) }}</span>
                <span class="text-sm text-brand-soft-textSec">módulos listos</span>
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($availableModules as $module)
                @php
                    $isActive = in_array($module['key'], $activeModuleKeys);
                @endphp
                <div wire:click="toggleModuleSelection('{{ $module['key'] }}')" 
                     class="flex items-start gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all
                     {{ $isActive ? 'border-brand-green-500 bg-brand-green-50/30' : 'border-gray-100 bg-white hover:border-gray-300' }}">
                    
                    <div class="mt-0.5 shrink-0">
                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $isActive ? 'border-brand-green-600 bg-brand-green-600' : 'border-gray-300 bg-white' }}">
                            @if($isActive)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-brand-navy-900 leading-tight">{{ $module['name'] }}</h4>
                        <p class="text-[11px] text-brand-soft-textSec mt-1 leading-snug">{{ $module['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Mapa de Integraciones Visual (Conceptual) -->
    @if(count($activeModuleKeys) > 1)
    <div>
        <h3 class="text-sm font-bold text-brand-soft-textSec uppercase tracking-widest mb-4 px-2">Cómo trabaja tu ecosistema</h3>
        <div class="p-6 bg-white rounded-3xl shadow-sm border border-brand-soft-border overflow-x-auto">
            <div class="flex items-center gap-4 min-w-max">
                @if(in_array('catalog', $activeModuleKeys))
                    <div class="px-4 py-2 bg-brand-soft-bg rounded-xl border border-brand-soft-borderDark font-medium text-sm text-brand-navy-900">Catálogo</div>
                    @if(in_array('inventory', $activeModuleKeys) || in_array('billing', $activeModuleKeys))
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    @endif
                @endif
                
                @if(in_array('billing', $activeModuleKeys))
                    <div class="px-4 py-2 bg-brand-soft-bg rounded-xl border border-brand-soft-borderDark font-medium text-sm text-brand-navy-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-green-500"></span> Facturación
                    </div>
                    @if(in_array('receivables', $activeModuleKeys) || in_array('inventory', $activeModuleKeys))
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    @endif
                @endif

                @if(in_array('receivables', $activeModuleKeys))
                    <div class="px-4 py-2 bg-brand-soft-bg rounded-xl border border-brand-soft-borderDark font-medium text-sm text-brand-navy-900">CxC</div>
                    @if(in_array('treasury', $activeModuleKeys))
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    @endif
                @endif

                @if(in_array('purchases', $activeModuleKeys))
                    <div class="px-4 py-2 bg-brand-soft-bg rounded-xl border border-brand-soft-borderDark font-medium text-sm text-brand-navy-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-coral-500"></span> Compras
                    </div>
                    @if(in_array('payables', $activeModuleKeys))
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    @endif
                @endif
                
                @if(in_array('payables', $activeModuleKeys))
                    <div class="px-4 py-2 bg-brand-soft-bg rounded-xl border border-brand-soft-borderDark font-medium text-sm text-brand-navy-900">CxP</div>
                    @if(in_array('treasury', $activeModuleKeys))
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    @endif
                @endif

                @if(in_array('treasury', $activeModuleKeys))
                    <div class="px-4 py-2 bg-brand-navy-900 text-white rounded-xl font-bold text-sm shadow-sm">Tesorería</div>
                @endif
            </div>
            <p class="text-xs text-brand-soft-textSec mt-4 text-center">Las conexiones muestran cómo viaja la información entre los módulos de tu empresa.</p>
        </div>
    </div>
    @endif

    <!-- Acciones -->
    <div class="flex justify-end pt-4">
        <button wire:click="saveConfiguration" wire:loading.attr="disabled" class="inline-flex items-center px-8 py-3 bg-brand-green-700 text-white font-bold rounded-xl shadow hover:bg-brand-green-800 focus:ring-4 focus:ring-brand-green-500/30 transition-all text-lg">
            <span wire:loading.remove>Confirmar Ecosistema</span>
            <span wire:loading>Configurando...</span>
        </button>
    </div>
</div>
