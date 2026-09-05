<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-[#F7FAF7] min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="font-gliker text-28pt text-brand-navy-900 leading-tight">Cuentas por Pagar</h1>
            <p class="font-sans text-16pt text-neutral-charcoal mt-1">Resumen de compromisos con proveedores</p>
        </div>
        <a href="/payables/list" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-sans text-sm font-semibold text-neutral-charcoal hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green-700 transition shadow-sm">
            Ver todas las CxP
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 flex flex-col justify-between">
            <h3 class="text-xs font-semibold text-neutral-charcoal uppercase tracking-wide mb-2 font-sans">Saldo Total Pendiente</h3>
            <p class="font-sans text-3xl font-bold text-brand-navy-900">
                C$ {{ number_format($indicators['total_balance'], 2) }}
            </p>
        </div>
        
        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 flex flex-col justify-between">
            <h3 class="text-xs font-semibold text-neutral-charcoal uppercase tracking-wide mb-2 font-sans">Saldo Vencido</h3>
            <p class="font-sans text-3xl font-bold {{ $indicators['overdue_balance'] > 0 ? 'text-brand-coral-500' : 'text-brand-navy-900' }}">
                C$ {{ number_format($indicators['overdue_balance'], 2) }}
            </p>
        </div>
        
        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 flex flex-col justify-between">
            <h3 class="text-xs font-semibold text-neutral-charcoal uppercase tracking-wide mb-2 font-sans">% de Cartera Vencida</h3>
            <p class="font-sans text-3xl font-bold text-brand-navy-900">
                {{ number_format($indicators['overdue_percentage'], 1) }}%
            </p>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                <div class="{{ $indicators['overdue_percentage'] > 30 ? 'bg-brand-coral-500' : 'bg-brand-green-500' }} h-2.5 rounded-full" style="width: {{ min($indicators['overdue_percentage'], 100) }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-sans text-lg font-bold text-brand-navy-900">Proveedores con más saldo</h3>
        </div>
        <div class="p-6">
            @if(count($indicators['top_suppliers']) > 0)
                <div class="space-y-4">
                    @foreach($indicators['top_suppliers'] as $row)
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                            <div>
                                <p class="font-sans font-semibold text-brand-navy-900">{{ $row->supplier->name ?? 'Desconocido' }}</p>
                            </div>
                            <p class="font-sans font-bold text-brand-navy-900">
                                C$ {{ number_format($row->total_balance, 2) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-neutral-charcoal font-sans text-sm text-center py-4">No hay saldos pendientes.</p>
            @endif
        </div>
    </div>
</div>
