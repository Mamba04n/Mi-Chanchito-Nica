<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-brand-navy-900">Clientes</h1>
            <p class="text-sm text-brand-soft-textSec">Administra tus clientes y su información comercial.</p>
        </div>
        <div>
            <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-green-700 text-white font-bold text-sm rounded-xl hover:bg-brand-green-800 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Nuevo cliente
            </button>
        </div>
    </div>

    <!-- Toolbar / Filters -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-brand-soft-border flex flex-col sm:flex-row gap-4">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre, correo o identificación..." class="block w-full pl-10 pr-3 py-2 border border-brand-soft-border rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-brand-green-500 focus:border-brand-green-500 sm:text-sm">
        </div>
    </div>

    <!-- Content -->
    @if($customers->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-12 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-brand-soft-bg rounded-full flex items-center justify-center mb-6 text-brand-soft-textSec">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-brand-navy-900">Aún no tienes clientes registrados</h3>
            <p class="text-sm text-brand-soft-textSec mt-2 mb-6 max-w-sm">Agrega a tus clientes para poder facturar y llevar su historial crediticio.</p>
            <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-navy-900 text-white font-bold text-sm rounded-xl hover:bg-brand-navy-800 shadow-sm transition-colors">
                Crear cliente
            </button>
        </div>
    @else
        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-brand-soft-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-brand-soft-border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Cliente</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Contacto</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Límite Crédito</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-brand-soft-border">
                        @foreach($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-brand-soft-bg rounded-full flex items-center justify-center text-brand-soft-textSec font-bold">
                                        {{ substr($customer->name, 0, 2) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-brand-navy-900">{{ $customer->name }}</div>
                                        <div class="text-[11px] text-brand-soft-textSec">{{ $customer->identification ?? 'Sin identificación' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-brand-navy-900">{{ $customer->email ?? '-' }}</div>
                                <div class="text-[11px] text-brand-soft-textSec">{{ $customer->phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-bold text-brand-navy-900">C$ {{ number_format($customer->credit_limit, 2) }}</div>
                                <div class="text-[11px] text-brand-soft-textSec">{{ $customer->credit_days }} días</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($customer->active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-brand-soft-textSec hover:text-brand-navy-900 mr-3">Editar</button>
                                @if($customer->active)
                                    <button wire:click="deactivate({{ $customer->id }})" class="text-brand-coral-500 hover:text-red-700">Desactivar</button>
                                @else
                                    <button wire:click="activate({{ $customer->id }})" class="text-brand-green-600 hover:text-brand-green-800">Activar</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-brand-soft-border bg-gray-50">
                {{ $customers->links() }}
            </div>
        </div>

        <!-- Mobile Cards (Visible only on Mobile) -->
        <div class="md:hidden space-y-4">
            @foreach($customers as $customer)
            <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-4 flex flex-col gap-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-bold text-brand-navy-900">{{ $customer->name }}</h4>
                        <p class="text-xs text-brand-soft-textSec">{{ $customer->identification ?? 'Sin ID' }}</p>
                    </div>
                    @if($customer->active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Activo</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800">Inactivo</span>
                    @endif
                </div>
                <div class="flex justify-between items-center text-sm">
                    <div>
                        <p class="text-xs text-brand-soft-textSec">Contacto</p>
                        <p class="font-medium text-brand-navy-900">{{ $customer->phone ?? $customer->email ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-brand-soft-textSec">Límite</p>
                        <p class="font-bold text-brand-navy-900">C$ {{ number_format($customer->credit_limit, 2) }}</p>
                    </div>
                </div>
                <div class="flex gap-2 mt-1 border-t border-brand-soft-border pt-3">
                    <button class="flex-1 py-1.5 text-xs font-bold text-brand-navy-900 bg-gray-100 rounded-lg hover:bg-gray-200">Editar</button>
                    @if($customer->active)
                        <button wire:click="deactivate({{ $customer->id }})" class="flex-1 py-1.5 text-xs font-bold text-brand-coral-500 bg-red-50 rounded-lg hover:bg-red-100">Desactivar</button>
                    @else
                        <button wire:click="activate({{ $customer->id }})" class="flex-1 py-1.5 text-xs font-bold text-brand-green-700 bg-brand-green-100 rounded-lg hover:bg-brand-green-200">Activar</button>
                    @endif
                </div>
            </div>
            @endforeach
            
            <div class="pt-2">
                {{ $customers->links() }}
            </div>
        </div>
    @endif
</div>