<div>
    <!-- Placeholder for Frontend Developer -->
    <h1>Administrar Usuarios</h1>
    
    @if (session()->has('message'))
        <div class="text-green-600">{{ session('message') }}</div>
    @endif

    <ul>
        @foreach($users as $user)
            <li>
                {{ $user->name }} - Rol actual: {{ $user->getRoleInCompany(app(\App\Context\CompanyContext::class)->currentCompany())?->name }}
                
                <select wire:change="changeRole({{ $user->id }}, $event.target.value)">
                    <option value="">Cambiar rol...</option>
                    @foreach($availableRoles as $role)
                        <option value="{{ $role->key }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </li>
        @endforeach
    </ul>
</div>
