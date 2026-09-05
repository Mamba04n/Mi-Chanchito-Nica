<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-display text-brand-navy-900">Catálogo</h1>
            <p class="text-sm text-brand-soft-textSec">Gestiona tus productos y servicios.</p>
        </div>
        <div>
            <!-- TODO: Replace with route to ProductForm when ready -->
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
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o SKU..." class="block w-full pl-10 pr-3 py-2 border border-brand-soft-border rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-brand-green-500 focus:border-brand-green-500 sm:text-sm">
        </div>
        <div class="w-full sm:w-48">
            <select wire:model.live="type" class="block w-full pl-3 pr-10 py-2 text-base border-brand-soft-border bg-gray-50 focus:outline-none focus:ring-brand-green-500 focus:border-brand-green-500 sm:text-sm rounded-xl">
                <option value="">Todos los tipos</option>
                <option value="product">Productos</option>
                <option value="service">Servicios</option>
            </select>
        </div>
    </div>

    <!-- Content -->
    @if($products->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-12 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-brand-soft-bg rounded-full flex items-center justify-center mb-6 text-brand-soft-textSec">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-brand-navy-900">Tu catálogo está vacío</h3>
            <p class="text-sm text-brand-soft-textSec mt-2 mb-6 max-w-sm">Agrega tu primer producto o servicio para comenzar a vender y llevar el control.</p>
            <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-navy-900 text-white font-bold text-sm rounded-xl hover:bg-brand-navy-800 shadow-sm transition-colors">
                Crear producto
            </button>
        </div>
    @else
        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-brand-soft-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-brand-soft-border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Producto</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Categoría</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Precio / Costo</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Control Stock</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-brand-soft-textSec uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-brand-soft-border">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-brand-soft-bg rounded-lg flex items-center justify-center text-brand-soft-textSec font-bold">
                                        {{ substr($product->name, 0, 2) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-brand-navy-900">{{ $product->name }}</div>
                                        <div class="text-[11px] text-brand-soft-textSec">{{ $product->sku }} • {{ $product->type === 'product' ? 'Producto' : 'Servicio' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-brand-navy-900">{{ $product->category?->name ?? 'Sin categoría' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-bold text-brand-navy-900">C$ {{ number_format($product->sale_price, 2) }}</div>
                                <div class="text-[11px] text-brand-soft-textSec">Costo: C$ {{ number_format($product->cost, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($product->type === 'product')
                                    @if($product->track_inventory)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-green-100 text-brand-green-800 border border-brand-green-200">Activo</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">No</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($product->active)
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
                                @if($product->active)
                                    <button wire:click="deactivate({{ $product->id }})" class="text-brand-coral-500 hover:text-red-700">Desactivar</button>
                                @else
                                    <button wire:click="activate({{ $product->id }})" class="text-brand-green-600 hover:text-brand-green-800">Activar</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-brand-soft-border bg-gray-50">
                {{ $products->links() }}
            </div>
        </div>

        <!-- Mobile Cards (Visible only on Mobile) -->
        <div class="md:hidden space-y-4">
            @foreach($products as $product)
            <div class="bg-white rounded-2xl shadow-sm border border-brand-soft-border p-4 flex flex-col gap-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-bold text-brand-navy-900">{{ $product->name }}</h4>
                        <p class="text-xs text-brand-soft-textSec">{{ $product->sku }}</p>
                    </div>
                    @if($product->active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Activo</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800">Inactivo</span>
                    @endif
                </div>
                <div class="flex justify-between items-center text-sm">
                    <div>
                        <p class="text-xs text-brand-soft-textSec">Categoría</p>
                        <p class="font-medium text-brand-navy-900">{{ $product->category?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-brand-soft-textSec">Precio</p>
                        <p class="font-bold text-brand-green-700">C$ {{ number_format($product->sale_price, 2) }}</p>
                    </div>
                </div>
                <div class="flex gap-2 mt-1 border-t border-brand-soft-border pt-3">
                    <button class="flex-1 py-1.5 text-xs font-bold text-brand-navy-900 bg-gray-100 rounded-lg hover:bg-gray-200">Editar</button>
                    @if($product->active)
                        <button wire:click="deactivate({{ $product->id }})" class="flex-1 py-1.5 text-xs font-bold text-brand-coral-500 bg-red-50 rounded-lg hover:bg-red-100">Desactivar</button>
                    @else
                        <button wire:click="activate({{ $product->id }})" class="flex-1 py-1.5 text-xs font-bold text-brand-green-700 bg-brand-green-100 rounded-lg hover:bg-brand-green-200">Activar</button>
                    @endif
                </div>
            </div>
            @endforeach
            
            <div class="pt-2">
                {{ $products->links() }}
            </div>
        </div>
    @endif
</div>