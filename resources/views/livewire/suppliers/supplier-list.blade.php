<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-brand-navy-900">Proveedores</h1>
            <p class="text-sm text-brand-soft-textSec">Gestiona tus proveedores y cuentas.</p>
        </div>
        <div>
            <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-green-700 text-white font-bold text-sm rounded-xl hover:bg-brand-green-800 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Nuevo registro
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
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar..." class="block w-full pl-10 pr-3 py-2 border border-brand-soft-border rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-brand-green-500 focus:border-brand-green-500 sm:text-sm">
        </div>
    </div>

    <!-- Content -->
    @if($suppliers->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-12 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-brand-soft-bg rounded-full flex items-center justify-center mb-6 text-brand-soft-textSec">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-brand-navy-900">Aún no hay registros</h3>
            <p class="text-sm text-brand-soft-textSec mt-2 mb-6 max-w-sm">No se encontraron Proveedores en la base de datos.</p>
        </div>
    @else
        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-brand-soft-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-brand-soft-border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Registro</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-brand-soft-border">
                        @foreach($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-brand-navy-900">{{ $supplier->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-brand-soft-textSec">{{ $supplier->identification ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-brand-navy-900">{{ $supplier->email ?? '-' }}</div>
                                <div class="text-[11px] text-brand-soft-textSec">{{ $supplier->phone ?? '-' }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-brand-soft-border bg-gray-50">
                {{ $suppliers->links() }}
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @foreach($suppliers as $supplier)
            <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-4 flex flex-col gap-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-bold text-brand-navy-900">{{ $supplier->name ?? 'N/A' }}</h4>
                        <p class="text-xs text-brand-soft-textSec">{{ $supplier->identification ?? '-' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="pt-2">
                {{ $suppliers->links() }}
            </div>
        </div>
    @endif
</div>