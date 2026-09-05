<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="flex items-center gap-3">
                <a href="/purchases" class="text-neutral-charcoal hover:text-brand-navy-900 transition">
                    &larr; Volver
                </a>
                <h1 class="font-gliker text-28pt text-brand-navy-900 leading-tight">
                    Compra {{ $purchase->number ?? 'Borrador' }}
                </h1>
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $purchase->status->value === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $purchase->status->value === 'confirmed' ? 'bg-brand-green-100 text-brand-green-700' : '' }}
                    {{ $purchase->status->value === 'cancelled' ? 'bg-brand-coral-100 text-brand-coral-700' : '' }}
                ">
                    {{ ucfirst($purchase->status->value) }}
                </span>
            </div>
            <p class="font-sans text-16pt text-neutral-charcoal mt-1">Proveedor: {{ $purchase->supplier->name ?? 'Sin Proveedor' }}</p>
        </div>
        
        <div class="flex gap-3">
            @if($purchase->status->value === 'draft')
                <a href="/purchases/{{ $purchase->id }}/edit" class="px-4 py-2 bg-white border border-gray-300 rounded-xl font-sans text-sm font-semibold text-neutral-charcoal hover:bg-gray-50 transition shadow-sm">
                    Editar
                </a>
                <button wire:click="confirmPurchase" class="px-4 py-2 bg-brand-green-700 text-white rounded-xl font-sans text-sm font-semibold hover:bg-brand-green-500 transition shadow-sm">
                    Confirmar Compra
                </button>
            @endif
            @if($purchase->status->value === 'confirmed')
                <button wire:click="cancelPurchase" class="px-4 py-2 bg-brand-coral-500 text-white rounded-xl font-sans text-sm font-semibold hover:bg-red-600 transition shadow-sm" onclick="return confirm('¿Está seguro de anular esta compra?')">
                    Anular Compra
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-brand-green-100 border border-brand-green-500 text-brand-green-700 px-4 py-3 rounded-xl font-sans text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 bg-brand-coral-500 bg-opacity-10 border border-brand-coral-500 text-brand-coral-500 px-4 py-3 rounded-xl font-sans text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100">
            <h3 class="text-xs font-semibold text-neutral-charcoal uppercase tracking-wide mb-2 font-sans">Información General</h3>
            <div class="space-y-2 font-sans text-sm">
                <p><span class="text-neutral-charcoal font-medium">Fecha:</span> {{ $purchase->purchase_date ? $purchase->purchase_date->format('d/m/Y') : '-' }}</p>
                <p><span class="text-neutral-charcoal font-medium">Vencimiento:</span> {{ $purchase->due_date ? $purchase->due_date->format('d/m/Y') : 'Contado' }}</p>
                <p><span class="text-neutral-charcoal font-medium">Almacén:</span> {{ $purchase->warehouse->name ?? 'N/A' }}</p>
            </div>
        </div>
        
        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100">
            <h3 class="text-xs font-semibold text-neutral-charcoal uppercase tracking-wide mb-2 font-sans">Proveedor</h3>
            <div class="space-y-2 font-sans text-sm">
                <p><span class="font-bold text-brand-navy-900">{{ $purchase->supplier->name ?? 'N/A' }}</span></p>
                <p class="text-neutral-charcoal">RUC/NIT: {{ $purchase->supplier->tax_id ?? 'N/A' }}</p>
                <p class="text-neutral-charcoal">{{ $purchase->supplier->email ?? '' }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 bg-gray-50 flex flex-col justify-center">
            <h3 class="text-xs font-semibold text-neutral-charcoal uppercase tracking-wide mb-2 font-sans text-right">Total Compra</h3>
            <p class="font-sans text-3xl font-bold text-brand-navy-900 text-right">
                C$ {{ number_format($purchase->total, 2) }}
            </p>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-sans text-lg font-bold text-brand-navy-900">Detalles</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-brand-navy-900 uppercase tracking-wider font-sans">Producto/Descripción</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-brand-navy-900 uppercase tracking-wider font-sans">Cantidad</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-brand-navy-900 uppercase tracking-wider font-sans">Costo Unitario</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-brand-navy-900 uppercase tracking-wider font-sans">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($purchase->items as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-sans text-brand-navy-900 font-medium">
                                {{ $item->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-sans text-neutral-charcoal text-right">
                                {{ number_format($item->quantity, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-sans text-neutral-charcoal text-right">
                                C$ {{ number_format($item->unit_cost, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-sans font-semibold text-brand-navy-900">
                                C$ {{ number_format($item->subtotal ?? ($item->quantity * $item->unit_cost), 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Trazabilidad / Timeline -->
    @if($purchase->status->value !== 'draft' && $purchase->status->value !== 'cancelled')
    <div class="mt-8">
        <h2 class="text-lg font-bold text-brand-navy-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Flujo Relacionado
        </h2>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
            <div class="flex items-center min-w-max gap-4">
                <!-- Compra Origin -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-brand-green-100 text-brand-green-700 flex items-center justify-center border-2 border-white shadow-sm z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-brand-navy-900 mt-2">Compra Confirmada</p>
                    <p class="text-xs text-brand-soft-textSec">{{ $purchase->confirmed_at ? $purchase->confirmed_at->format('d/m/Y') : 'Sí' }}</p>
                </div>
                
                <div class="w-12 h-0.5 bg-gray-200 -mt-8"></div>
                <svg class="w-4 h-4 text-gray-300 -mt-8 -ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>

                <!-- Inventario -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center border-2 border-white shadow-sm z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-brand-navy-900 mt-2">Entrada de Inventario</p>
                    <p class="text-xs text-brand-soft-textSec">Automático</p>
                </div>

                @if($purchase->purchase_type === 'credit' || $purchase->due_date)
                    <div class="w-12 h-0.5 bg-gray-200 -mt-8"></div>
                    <svg class="w-4 h-4 text-gray-300 -mt-8 -ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>

                    <!-- CxP -->
                    <div class="flex flex-col items-center">
                        <a href="/payables" class="w-12 h-12 rounded-full bg-brand-coral-50 text-brand-coral-600 flex items-center justify-center border-2 border-white shadow-sm z-10 hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                        </a>
                        <p class="text-sm font-bold text-brand-navy-900 mt-2">Cuenta por Pagar</p>
                        <p class="text-xs text-brand-coral-600 font-semibold">{{ number_format($purchase->total, 2) }}</p>
                    </div>
                @else
                    <div class="w-12 h-0.5 bg-gray-200 -mt-8"></div>
                    <svg class="w-4 h-4 text-gray-300 -mt-8 -ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>

                    <!-- Tesorería Directa -->
                    <div class="flex flex-col items-center">
                        <a href="/treasury" class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center border-2 border-white shadow-sm z-10 hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </a>
                        <p class="text-sm font-bold text-brand-navy-900 mt-2">Salida de Caja</p>
                        <p class="text-xs text-yellow-600 font-semibold">-{{ number_format($purchase->total, 2) }}</p>
                    </div>
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-4 text-center">Este flujo se registró automáticamente en tu empresa.</p>
        </div>
    </div>
    @endif
</div>
