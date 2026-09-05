<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#172238] font-gliker">Cuentas por Cobrar</h1>
        <p class="text-sm text-gray-500 font-sans">Resumen de cartera y estado de deuda</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- KPI: Total -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-500 mb-2 font-sans">Total por Cobrar</h3>
            <div class="text-3xl font-bold text-[#172238] font-sans">
                C$ {{ number_format($indicators['total'] ?? 0, 2) }}
            </div>
        </div>

        <!-- KPI: Vencido -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-500 mb-2 font-sans">Total Vencido</h3>
            <div class="text-3xl font-bold text-[#D98572] font-sans">
                C$ {{ number_format($indicators['overdue'] ?? 0, 2) }}
            </div>
        </div>

        <!-- KPI: Porcentaje Vencido -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-500 mb-2 font-sans">% Vencido</h3>
            <div class="text-3xl font-bold text-[#172238] font-sans">
                {{ number_format($indicators['percentage_overdue'] ?? 0, 1) }}%
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-[#172238] font-sans">Top Deudores</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left font-sans">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Vencido</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($indicators['top_debtors'] ?? [] as $debtor)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-[#172238]">
                                {{ $debtor['name'] ?? 'Desconocido' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                C$ {{ number_format($debtor['balance'] ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[#D98572] font-medium">
                                C$ {{ number_format($debtor['overdue_balance'] ?? 0, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                                No hay deudores para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
