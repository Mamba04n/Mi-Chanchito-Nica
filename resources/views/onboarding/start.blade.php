<x-guest-layout>
    <div class="p-6">
        <h2 class="text-2xl font-display text-brand-navy-900 mb-4">Bienvenido a Mi Chanchito Nica</h2>
        <p class="text-brand-navy-900 mb-6">Para comenzar, crea el perfil de tu empresa.</p>

        <form method="POST" action="{{ route('onboarding.store') }}">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-brand-navy-900">Nombre de la Empresa</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500" required>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-brand-navy-900 mb-2">Selecciona los módulos a usar</h3>
                @php
                    $modules = \App\Models\Module::where('active', true)->get();
                @endphp
                <div class="space-y-2">
                    @foreach($modules as $module)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="modules[]" value="{{ $module->key }}" class="rounded border-gray-300 text-brand-green-500 focus:ring-brand-green-500">
                            <span class="ml-2 text-brand-navy-900">{{ $module->name }}</span>
                        </label>
                        <p class="text-sm text-gray-500 ml-6">{{ $module->description }}</p>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-brand-green-700 text-neutral-white px-4 py-2 rounded-md hover:bg-brand-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green-500">
                    Crear Empresa
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
