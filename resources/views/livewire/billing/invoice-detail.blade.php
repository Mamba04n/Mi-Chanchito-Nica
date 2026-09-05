<div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8 font-sans">
    <!-- Encabezado y Acciones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-2xl font-bold text-[#172238] font-gliker">Factura {{ $invoice->number ?? '# Borrador' }}</h1>
                @if($invoice->status === 'confirmed')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-[#1D6B46]">Confirmada</span>
                @elseif($invoice->status === 'cancelled')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                @else
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-[#E9B63D]">Borrador</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">Emitida el {{ $invoice->issue_date ? (is_string($invoice->issue_date) ? \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') : $invoice->issue_date->format('d/m/Y')) : '-' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="/billing/invoices" class="px-4 py-2 border border-gray-300 rounded-xl shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1D6B46]">
                Volver
            </a>
            @if($invoice->status === 'draft')
                <a href="/billing/invoices/{{ $invoice->id }}/edit" class="px-4 py-2 border border-gray-300 rounded-xl shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1D6B46]">
                    Editar
                </a>
                <button wire:click="confirmInvoice" class="px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#1D6B46] hover:bg-[#155436] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1D6B46]">
                    Confirmar Factura
                </button>
            @endif
            @if($invoice->status !== 'cancelled')
                <button wire:click="cancelInvoice" wire:confirm="¿Estás seguro de cancelar esta factura?" class="px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Cancelar Factura
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 p-4 rounded-xl border border-green-200 text-sm text-[#1D6B46]">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 p-4 rounded-xl border border-red-200 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detalles Principales -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-[#172238] mb-4 border-b border-gray-100 pb-2">Artículos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider py-2">Descripción</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider py-2">Cant.</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider py-2">Precio</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($invoice->items as $item)
                                <tr>
                                    <td class="py-3 text-sm text-[#172238]">{{ $item->description ?? 'Producto ' . $item->product_id }}</td>
                                    <td class="py-3 text-sm text-gray-700 text-right">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="py-3 text-sm text-gray-700 text-right">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 text-sm font-semibold text-[#172238] text-right">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-sm text-gray-500">Sin artículos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($invoice->notes)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-[#172238] mb-2">Notas Adicionales</h2>
                <p class="text-sm text-gray-600">{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Panel Lateral (Resumen) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-[#172238] mb-4">Información del Cliente</h2>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold text-[#172238] text-base">{{ $invoice->customer->name ?? 'Cliente Desconocido' }}</p>
                    <p class="mt-1 text-gray-500">ID: {{ $invoice->customer_id }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-[#172238] mb-4">Resumen de Totales</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-semibold text-[#172238]">{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Descuento</span>
                        <span class="font-semibold text-[#172238]">-{{ number_format($invoice->discount_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Impuestos</span>
                        <span class="font-semibold text-[#172238]">{{ number_format($invoice->tax_total, 2) }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                        <span class="text-base font-bold text-[#172238]">Total ({{ $invoice->currency }})</span>
                        <span class="text-xl font-bold text-[#1D6B46]">{{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-4 text-xs text-gray-500">
                <p>Tipo de Venta: <span class="font-semibold text-[#172238] capitalize">{{ $invoice->sale_type }}</span></p>
                @if($invoice->due_date)
                    <p class="mt-1">Vence el: <span class="font-semibold text-[#172238]">{{ is_string($invoice->due_date) ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : $invoice->due_date->format('d/m/Y') }}</span></p>
                @endif
            </div>
        </div>
    </div>

    <!-- Trazabilidad / Timeline -->
    @if($invoice->status !== 'draft' && $invoice->status !== 'cancelled')
    <div class="mt-8">
        <h2 class="text-lg font-bold text-[#172238] mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Flujo Relacionado
        </h2>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
            <div class="flex items-center min-w-max gap-4">
                <!-- Factura Origin -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-brand-green-100 text-brand-green-700 flex items-center justify-center border-2 border-white shadow-sm z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-brand-navy-900 mt-2">Factura Confirmada</p>
                    <p class="text-xs text-brand-soft-textSec">{{ is_string($invoice->confirmed_at) ? \Carbon\Carbon::parse($invoice->confirmed_at)->format('d/m/Y') : ($invoice->confirmed_at ? $invoice->confirmed_at->format('d/m/Y') : 'Sí') }}</p>
                </div>
                
                <div class="w-12 h-0.5 bg-gray-200 -mt-8"></div>
                <svg class="w-4 h-4 text-gray-300 -mt-8 -ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>

                <!-- Inventario -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center border-2 border-white shadow-sm z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-brand-navy-900 mt-2">Salida de Inventario</p>
                    <p class="text-xs text-brand-soft-textSec">Automático</p>
                </div>

                @if($invoice->sale_type === 'credit')
                    <div class="w-12 h-0.5 bg-gray-200 -mt-8"></div>
                    <svg class="w-4 h-4 text-gray-300 -mt-8 -ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>

                    <!-- CxC -->
                    <div class="flex flex-col items-center">
                        <a href="/receivables" class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center border-2 border-white shadow-sm z-10 hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </a>
                        <p class="text-sm font-bold text-brand-navy-900 mt-2">Cuenta por Cobrar</p>
                        <p class="text-xs text-blue-600 font-semibold">{{ number_format($invoice->total, 2) }} {{ $invoice->currency }}</p>
                    </div>
                @else
                    <div class="w-12 h-0.5 bg-gray-200 -mt-8"></div>
                    <svg class="w-4 h-4 text-gray-300 -mt-8 -ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>

                    <!-- Tesorería Directa -->
                    <div class="flex flex-col items-center">
                        <a href="/treasury" class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center border-2 border-white shadow-sm z-10 hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </a>
                        <p class="text-sm font-bold text-brand-navy-900 mt-2">Ingreso a Caja</p>
                        <p class="text-xs text-yellow-600 font-semibold">+{{ number_format($invoice->total, 2) }} {{ $invoice->currency }}</p>
                    </div>
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-4 text-center">Este flujo se registró automáticamente en tu empresa.</p>
        </div>
    </div>
    @endif
</div>
