<div x-data="{ show: @entangle('show') }" 
     x-show="show" 
     style="display: none;" 
     class="fixed inset-0 z-50 flex flex-col bg-brand-soft-bg/95 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
    
    <div class="flex items-center justify-between p-6">
        <h2 class="text-3xl font-display font-bold text-brand-navy-900">Aplicaciones</h2>
        <button wire:click="toggle" class="p-2 rounded-full bg-white text-brand-soft-textSec hover:text-brand-navy-900 shadow-sm border border-brand-soft-border">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-6 pt-0">
        <p class="text-brand-soft-textSec mb-8 text-lg">Todo lo que tu empresa utiliza</p>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($modules as $module)
                <a href="{{ route($module['route']) }}" wire:click="toggle" class="group flex flex-col items-center text-center p-6 bg-white rounded-3xl shadow-sm border border-brand-soft-border hover:shadow-md hover:border-brand-green-500 transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-brand-soft-bg text-brand-green-700 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-brand-green-50 transition-all">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $module['icon'] !!}
                        </svg>
                    </div>
                    <h3 class="font-bold text-brand-navy-900 mb-1 group-hover:text-brand-green-700 transition-colors">{{ $module['name'] }}</h3>
                    <p class="text-xs text-brand-soft-textSec leading-tight">{{ $module['desc'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            <a href="{{ route('settings.modules') }}" wire:navigate wire:click="toggle" class="inline-flex items-center px-6 py-3 bg-brand-navy-900 text-white font-bold text-sm rounded-xl hover:bg-brand-navy-800 shadow-sm transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Configurar aplicaciones (Packs y Módulos)
            </a>
        </div>
    </div>
</div>
