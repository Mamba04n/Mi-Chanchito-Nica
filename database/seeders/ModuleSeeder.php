<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'key' => 'customers',
                'name' => 'Clientes',
                'description' => 'Gestión de cartera de clientes.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'suppliers',
                'name' => 'Proveedores',
                'description' => 'Gestión de proveedores.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'catalog',
                'name' => 'Catálogo',
                'description' => 'Productos y servicios.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'billing',
                'name' => 'Facturación',
                'description' => 'Facturación y cotizaciones.',
                'active' => true,
                'dependencies' => ['customers', 'catalog'],
            ],
            [
                'key' => 'inventory',
                'name' => 'Inventario',
                'description' => 'Control de existencias y almacenes.',
                'active' => true,
                'dependencies' => ['catalog'],
            ],
            [
                'key' => 'purchases',
                'name' => 'Compras',
                'description' => 'Compras y órdenes.',
                'active' => true,
                'dependencies' => ['suppliers', 'catalog'],
            ],
            [
                'key' => 'receivables',
                'name' => 'Cuentas por Cobrar',
                'description' => 'Control de deudas de clientes y abonos.',
                'active' => true,
                'dependencies' => ['billing'],
            ],
            [
                'key' => 'payables',
                'name' => 'Cuentas por Pagar',
                'description' => 'Obligaciones de pago a proveedores.',
                'active' => true,
                'dependencies' => ['purchases'],
            ],
            [
                'key' => 'treasury',
                'name' => 'Caja y Bancos',
                'description' => 'Cuentas, ingresos y egresos.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'reports',
                'name' => 'Reportes',
                'description' => 'Reportes financieros y métricas.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'education',
                'name' => 'Academia',
                'description' => 'Rutas de aprendizaje financiero.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'gamification',
                'name' => 'Gamificación',
                'description' => 'XP, niveles, rachas y logros.',
                'active' => true,
                'dependencies' => ['education'],
            ],
            [
                'key' => 'ai',
                'name' => 'Coach IA',
                'description' => 'Asistente de inteligencia artificial.',
                'active' => true,
                'dependencies' => [],
            ],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(['key' => $module['key']], $module);
        }
    }
}
