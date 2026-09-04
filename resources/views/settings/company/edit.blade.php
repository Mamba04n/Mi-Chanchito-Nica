<div>
    <!-- Placeholder for Frontend Developer -->
    <h1>Editar Empresa</h1>
    
    @if (session()->has('message'))
        <div class="text-green-600">{{ session('message') }}</div>
    @endif

    <form wire:submit="save">
        <input type="text" wire:model="name" placeholder="Nombre">
        <input type="text" wire:model="country_code" placeholder="País">
        <input type="text" wire:model="currency_code" placeholder="Moneda">
        <input type="text" wire:model="timezone" placeholder="Zona Horaria">
        
        <button type="submit">Guardar</button>
    </form>
</div>
