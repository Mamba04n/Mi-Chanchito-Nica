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

        $moduleManager->activateModule($company, 'inventory');
        
        $warehouseMain = \App\Models\Warehouse::create([
            'company_id' => $company->id,
            'code' => 'MAIN',
            'name' => 'Bodega Principal',
            'is_default' => true,
        ]);
        
        $warehouseBranch = \App\Models\Warehouse::create([
            'company_id' => $company->id,
            'code' => 'SUC-MGA',
            'name' => 'Sucursal Managua',
        ]);
        app(\App\Context\CompanyContext::class)->setCompany($company);
        auth()->login($user);

        $inventoryService = app(\App\Services\Inventory\InventoryService::class);
        $prodCoffee = \App\Models\Product::where('sku', 'PROD-001')->first(); // Café (normal)
        $prodSugar = \App\Models\Product::where('sku', 'PROD-002')->first(); // Azúcar (agotado)
        $prodGlass = \App\Models\Product::where('sku', 'PROD-003')->first(); // Vaso (bajo stock)

        // Set minimum stocks directly
        \App\Models\InventoryStock::create([
            'company_id' => $company->id,
            'warehouse_id' => $warehouseMain->id,
            'product_id' => $prodCoffee->id,
            'minimum_stock' => 20,
        ]);
        
        \App\Models\InventoryStock::create([
            'company_id' => $company->id,
            'warehouse_id' => $warehouseMain->id,
            'product_id' => $prodGlass->id,
            'minimum_stock' => 50,
        ]);

        // Coffee: opening stock 120, transfer 20 to branch.
        $inventoryService->setOpeningStock($warehouseMain, $prodCoffee, 120, 'Stock inicial inventario');
        $inventoryService->transferStock($warehouseMain, $warehouseBranch, $prodCoffee, 20, 'Abastecer sucursal');

        // Glass: low stock. minimum 50, set opening to 30.
        $inventoryService->setOpeningStock($warehouseMain, $prodGlass, 30, 'Stock inicial vasos térmicos');
        
        // Sugar is left at 0 (agotado). No opening stock needed.
    }
}
