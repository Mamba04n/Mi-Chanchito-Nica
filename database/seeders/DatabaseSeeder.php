<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Services\ModuleManager;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            ModuleSeeder::class,
        ]);

        $user = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@chanchitonica.com',
            'password' => bcrypt('password'),
        ]);

        $company = Company::create([
            'name' => 'Pulpería El Progreso',
            'country_code' => 'NI',
            'currency_code' => 'NIO',
            'timezone' => 'America/Managua',
            'active' => true,
        ]);

        $ownerRole = \App\Models\Role::where('key', 'owner')->first();

        $company->users()->attach($user, [
            'role_id' => $ownerRole->id,
            'status' => 'active',
        ]);

        $moduleManager = app(\App\Services\ModuleManager::class);
        $moduleManager->activateModule($company, 'customers');
        $moduleManager->activateModule($company, 'suppliers');
        $moduleManager->activateModule($company, 'catalog');
        $moduleManager->activateModule($company, 'billing');
        $moduleManager->activateModule($company, 'treasury');

        // Seed base catalogs
        // Context requires active_company_id for the Global Scope to pick it up in console easily,
        // but we can also just explicitly assign company_id when not running through a request.
        
        $catGroceries = \App\Models\ProductCategory::create(['company_id' => $company->id, 'name' => 'Abarrotes']);
        $catHardware = \App\Models\ProductCategory::create(['company_id' => $company->id, 'name' => 'Ferretería']);
        $catServices = \App\Models\ProductCategory::create(['company_id' => $company->id, 'name' => 'Servicios']);

        $uomUnit = \App\Models\UnitOfMeasure::create(['company_id' => $company->id, 'name' => 'Unidad', 'abbreviation' => 'Un']);
        $uomKg = \App\Models\UnitOfMeasure::create(['company_id' => $company->id, 'name' => 'Kilogramo', 'abbreviation' => 'kg']);
        $uomHour = \App\Models\UnitOfMeasure::create(['company_id' => $company->id, 'name' => 'Hora', 'abbreviation' => 'h']);

        \App\Models\Customer::create(['company_id' => $company->id, 'name' => 'Distribuidora San Juan']);
        \App\Models\Customer::create(['company_id' => $company->id, 'name' => 'Pulpería La Esperanza', 'credit_limit' => 5000]);
        \App\Models\Customer::create(['company_id' => $company->id, 'name' => 'Café Central']);

        \App\Models\Supplier::create(['company_id' => $company->id, 'name' => 'Distribuidora Nacional', 'payment_terms_days' => 15]);
        \App\Models\Supplier::create(['company_id' => $company->id, 'name' => 'Empaques del Pacífico']);

        \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'PROD-001', 'name' => 'Café molido 400 g', 
            'category_id' => $catGroceries->id, 'unit_id' => $uomUnit->id, 
            'type' => 'product', 'sale_price' => 120.00, 'cost' => 80.00, 'track_inventory' => true
        ]);
        
        \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'PROD-002', 'name' => 'Azúcar 1 kg', 
            'category_id' => $catGroceries->id, 'unit_id' => $uomKg->id, 
            'type' => 'product', 'sale_price' => 45.00, 'cost' => 35.00, 'track_inventory' => true
        ]);

        \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'PROD-003', 'name' => 'Vaso térmico', 
            'category_id' => $catHardware->id, 'unit_id' => $uomUnit->id, 
            'type' => 'product', 'sale_price' => 350.00, 'cost' => 200.00, 'track_inventory' => true
        ]);

        \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'SERV-001', 'name' => 'Consultoría administrativa', 
            'category_id' => $catServices->id, 'unit_id' => $uomHour->id, 
            'type' => 'service', 'sale_price' => 1500.00, 'cost' => 0.00, 'track_inventory' => false
        ]);

        \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'SERV-002', 'name' => 'Instalación', 
            'category_id' => $catServices->id, 'unit_id' => $uomUnit->id, 
            'type' => 'service', 'sale_price' => 800.00, 'cost' => 0.00, 'track_inventory' => false
        ]);

        \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'SERV-003', 'name' => 'Capacitación', 
            'category_id' => $catServices->id, 'unit_id' => $uomHour->id, 
            'type' => 'service', 'sale_price' => 500.00, 'cost' => 0.00, 'track_inventory' => false
        ]);
    }
}
