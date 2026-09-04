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
                'key' => 'sales',
                'name' => 'Ventas y Facturación',
                'description' => 'Facturación, clientes, cotizaciones y ventas.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'inventory',
                'name' => 'Inventario',
                'description' => 'Control de productos, servicios, existencias y almacenes.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'purchases',
                'name' => 'Compras',
                'description' => 'Proveedores, compras, órdenes y recepciones.',
                'active' => true,
                'dependencies' => [],
            ],
            [
                'key' => 'receivables',
                'name' => 'Cuentas por Cobrar',
                'description' => 'Control de deudas de clientes, abonos y vencimientos.',
                'active' => true,
                'dependencies' => ['sales'],
            ],
            [
                'key' => 'payables',
                'name' => 'Cuentas por Pagar',
                'description' => 'Obligaciones de pago a proveedores y abonos.',
                'active' => true,
                'dependencies' => ['purchases'],
            ],
            [
                'key' => 'cash',
                'name' => 'Caja y Bancos',
                'description' => 'Cuentas financieras, movimientos, ingresos y egresos.',
                'active' => true,
                'dependencies' => [],
            ],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(['key' => $module['key']], $module);
        }
    }
}
