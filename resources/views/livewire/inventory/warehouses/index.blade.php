<div>
    <x-slot name="header">
        @include('modules.inventory.components.nav')
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session()->has('success'))
                <div class="p-4 bg-brand-green-500 bg-opacity-10 border border-brand-green-500 rounded-md">
                    <p class="text-sm text-brand-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-brand-navy-900">Almacenes</h3>
                        <p class="text-sm text-gray-500 mt-1">Gestiona las bodegas y sucursales de tu empresa.</p>
                    </div>
                    
                    <button wire:click="create" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-brand-green-700 border border-transparent rounded-md shadow-sm hover:bg-brand-green-500 focus:outline-none">
                        + Nuevo Almacén
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($warehouses as $wh)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $wh->code }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                            {{ $wh->name }}
                                            @if($wh->is_default)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-gold-500 bg-opacity-20 text-yellow-800">
                                                    Principal
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $wh->address ?: 'Sin dirección registrada' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($wh->active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-green-500 bg-opacity-20 text-brand-green-700">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="edit({{ $wh->id }})" class="text-brand-green-700 hover:text-brand-green-500 mr-3">Editar</button>
                                        @if($wh->active)
                                            <button wire:click="deactivate({{ $wh->id }})" wire:confirm="¿Seguro que deseas desactivar este almacén?" class="text-brand-coral-500 hover:text-red-700">Desactivar</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <p class="text-sm text-gray-500">No hay almacenes registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Modal para Crear/Editar -->
            @if($showModal)
                <div class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center">
                    <!-- Overlay -->
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75" wire:click="$set('showModal', false)"></div>
                    </div>

                    <!-- Panel -->
                    <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full relative z-50">
                        <form wire:submit.prevent="save">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    {{ $editId ? 'Editar Almacén' : 'Nuevo Almacén' }}
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                                        <input type="text" wire:model="code" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                        @error('code') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                        <input type="text" wire:model="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                        @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                        <input type="text" wire:model="address" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                        <textarea wire:model="description" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-green-500 focus:ring-brand-green-500 sm:text-sm"></textarea>
                                    </div>

                                    <div class="flex items-center mt-2">
                                        <input type="checkbox" wire:model="is_default" class="rounded border-gray-300 text-brand-green-700 focus:ring-brand-green-500">
                                        <label class="ml-2 block text-sm text-gray-900">Establecer como almacén principal</label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="active" class="rounded border-gray-300 text-brand-green-700 focus:ring-brand-green-500">
                                        <label class="ml-2 block text-sm text-gray-900">Activo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-green-700 text-base font-medium text-white hover:bg-brand-green-500 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    Guardar
                                </button>
                                <button type="button" wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
