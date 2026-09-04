<div>
    <!-- Placeholder for Frontend Developer -->
    <h1>Módulos de la Empresa</h1>
    
    @if (session()->has('message'))
        <div class="text-green-600">{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="text-red-600">{{ session('error') }}</div>
    @endif

    <ul>
        @foreach($availableModules as $module)
            <li>
                {{ $module->name }} 
                <button wire:click="toggleModule('{{ $module->key }}')">
                    {{ in_array($module->key, $activeModuleKeys) ? 'Desactivar' : 'Activar' }}
                </button>
            </li>
        @endforeach
    </ul>
</div>
