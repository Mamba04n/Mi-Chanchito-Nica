<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="space-y-6">
        @if (session()->has('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-brand-green-700 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm text-brand-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 bg-opacity-50">
                <div>
                    <h3 class="text-lg font-bold text-brand-navy-900">Almacenes Registrados</h3>
                    <p class="text-sm text-gray-500 mt-1">Gestiona las bodegas y sucursales de tu empresa.</p>
                </div>
                
                <button wire:click="create" class="inline-flex justify-center items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-brand-green-700 border border-transparent rounded-lg shadow-sm hover:bg-brand-green-500 transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo Almacén
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50 bg-opacity-50">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($warehouses as $wh)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $wh->code }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        {{ $wh->name }}
                                        @if($wh->is_default)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-brand-gold-500 bg-opacity-20 text-yellow-800">
                                                Principal
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $wh->address ?: 'Sin dirección registrada' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($wh->active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-brand-green-700 border border-green-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-green-500"></span> Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $wh->id }})" class="text-brand-navy-900 hover:text-brand-green-700 transition-colors mr-4">Editar</button>
                                    @if($wh->active && !$wh->is_default)
                                        <button wire:click="deactivate({{ $wh->id }})" wire:confirm="¿Seguro que deseas desactivar este almacén?" class="text-brand-coral-500 hover:text-red-700 transition-colors">Desactivar</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <p class="text-base font-medium text-gray-900">No hay almacenes registrados</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Modal para Crear/Editar -->
        @if($showModal)
            <div class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" wire:click="$set('showModal', false)"></div>

                <!-- Panel -->
                <div class="bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full relative z-50">
                    <form wire:submit.prevent="save">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-full bg-brand-green-50 flex items-center justify-center text-brand-green-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ $editId ? 'Editar Almacén' : 'Nuevo Almacén' }}
                                </h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                                    <input type="text" wire:model="code" required class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                                    @error('code') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                    <input type="text" wire:model="name" required class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                                    @error('name') <span class="text-xs text-brand-coral-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                    <input type="text" wire:model="address" class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                    <textarea wire:model="description" rows="2" class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-green-500 focus:border-brand-green-500"></textarea>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 mt-2 border border-gray-100 space-y-3">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="is_default" class="rounded border-gray-300 text-brand-green-700 focus:ring-brand-green-500">
                                        <span class="ml-2 text-sm text-gray-900 font-medium">Establecer como almacén principal</span>
                                    </label>
                                    
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="active" class="rounded border-gray-300 text-brand-green-700 focus:ring-brand-green-500">
                                        <span class="ml-2 text-sm text-gray-900 font-medium">Almacén Activo</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" wire:click="$set('showModal', false)" class="inline-flex justify-center rounded-lg border border-gray-200 px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent px-5 py-2 bg-brand-green-700 text-sm font-bold text-white hover:bg-brand-green-500 focus:outline-none transition-colors">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</div>
