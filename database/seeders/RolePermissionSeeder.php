<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create base roles
        $owner = Role::updateOrCreate(['key' => 'owner'], [
            'name' => 'Propietario',
            'description' => 'Acceso total y administración de la empresa.'
        ]);

        $admin = Role::updateOrCreate(['key' => 'admin'], [
            'name' => 'Administrador',
            'description' => 'Administración general, pero no puede borrar la empresa.'
        ]);

        $manager = Role::updateOrCreate(['key' => 'manager'], [
            'name' => 'Gerente',
            'description' => 'Gestión operativa, compras, ventas e inventario.'
        ]);

        $operator = Role::updateOrCreate(['key' => 'operator'], [
            'name' => 'Operador',
            'description' => 'Operaciones diarias (facturación, recibos).'
        ]);

        $viewer = Role::updateOrCreate(['key' => 'viewer'], [
            'name' => 'Espectador',
            'description' => 'Solo lectura.'
        ]);

        // Create base permissions
        $permissions = [
            ['key' => 'inventory.view', 'name' => 'Ver inventario', 'module_key' => 'inventory'],
            ['key' => 'inventory.create', 'name' => 'Crear productos', 'module_key' => 'inventory'],
            ['key' => 'inventory.update', 'name' => 'Editar productos', 'module_key' => 'inventory'],
            ['key' => 'inventory.adjust', 'name' => 'Ajustar existencias', 'module_key' => 'inventory'],

            ['key' => 'billing.view', 'name' => 'Ver facturación', 'module_key' => 'billing'],
            ['key' => 'billing.create', 'name' => 'Crear facturas', 'module_key' => 'billing'],
            ['key' => 'billing.confirm', 'name' => 'Confirmar facturas', 'module_key' => 'billing'],
            ['key' => 'billing.cancel', 'name' => 'Anular facturas', 'module_key' => 'billing'],
            
            ['key' => 'modules.manage', 'name' => 'Administrar módulos', 'module_key' => 'core'],
            ['key' => 'users.manage', 'name' => 'Administrar usuarios', 'module_key' => 'core'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['key' => $perm['key']], $perm);
        }

        // Assign some permissions to roles (Owner has all implicitly via code, but let's assign to admin/manager)
        $allPermissions = Permission::all();
        $admin->permissions()->sync($allPermissions);

        $managerPermissions = Permission::whereIn('key', [
            'inventory.view', 'inventory.create', 'inventory.update',
            'billing.view', 'billing.create', 'billing.confirm'
        ])->get();
        $manager->permissions()->sync($managerPermissions);

        $operatorPermissions = Permission::whereIn('key', [
            'inventory.view', 'billing.view', 'billing.create'
        ])->get();
        $operator->permissions()->sync($operatorPermissions);

        $viewerPermissions = Permission::whereIn('key', [
            'inventory.view', 'billing.view'
        ])->get();
        $viewer->permissions()->sync($viewerPermissions);
    }
}
