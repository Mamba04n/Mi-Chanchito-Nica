<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Services\ModuleManager;
use App\Enums\InvoiceStatus;
use App\Enums\SaleType;
use App\Enums\ReceivableStatus;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            ModuleSeeder::class,
            EducationSeeder::class,
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
        $moduleManager->activateModule($company, 'receivables');
        $moduleManager->activateModule($company, 'treasury');
        $moduleManager->activateModule($company, 'inventory');

        $catGroceries = \App\Models\ProductCategory::create(['company_id' => $company->id, 'name' => 'Abarrotes']);
        $catHardware = \App\Models\ProductCategory::create(['company_id' => $company->id, 'name' => 'Ferretería']);
        $catServices = \App\Models\ProductCategory::create(['company_id' => $company->id, 'name' => 'Servicios']);

        $uomUnit = \App\Models\UnitOfMeasure::create(['company_id' => $company->id, 'name' => 'Unidad', 'abbreviation' => 'Un']);
        $uomKg = \App\Models\UnitOfMeasure::create(['company_id' => $company->id, 'name' => 'Kilogramo', 'abbreviation' => 'kg']);
        $uomHour = \App\Models\UnitOfMeasure::create(['company_id' => $company->id, 'name' => 'Hora', 'abbreviation' => 'h']);

        $cust1 = \App\Models\Customer::create(['company_id' => $company->id, 'name' => 'Distribuidora San Juan']);
        $cust2 = \App\Models\Customer::create(['company_id' => $company->id, 'name' => 'Pulpería La Esperanza', 'credit_limit' => 5000]);
        $cust3 = \App\Models\Customer::create(['company_id' => $company->id, 'name' => 'Café Central']);

        \App\Models\Supplier::create(['company_id' => $company->id, 'name' => 'Distribuidora Nacional', 'payment_terms_days' => 15]);
        \App\Models\Supplier::create(['company_id' => $company->id, 'name' => 'Empaques del Pacífico']);

        $prodCoffee = \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'PROD-001', 'name' => 'Café molido 400 g', 
            'category_id' => $catGroceries->id, 'unit_id' => $uomUnit->id, 
            'type' => 'product', 'sale_price' => 120.00, 'cost' => 80.00, 'track_inventory' => true
        ]);
        
        $prodSugar = \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'PROD-002', 'name' => 'Azúcar 1 kg', 
            'category_id' => $catGroceries->id, 'unit_id' => $uomKg->id, 
            'type' => 'product', 'sale_price' => 45.00, 'cost' => 35.00, 'track_inventory' => true
        ]);

        $prodGlass = \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'PROD-003', 'name' => 'Vaso térmico', 
            'category_id' => $catHardware->id, 'unit_id' => $uomUnit->id, 
            'type' => 'product', 'sale_price' => 350.00, 'cost' => 200.00, 'track_inventory' => true
        ]);

        $servAdmin = \App\Models\Product::create([
            'company_id' => $company->id, 'sku' => 'SERV-001', 'name' => 'Consultoría administrativa', 
            'category_id' => $catServices->id, 'unit_id' => $uomHour->id, 
            'type' => 'service', 'sale_price' => 1500.00, 'cost' => 0.00, 'track_inventory' => false
        ]);
        
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
        
        \App\Models\InventoryStock::create([
            'company_id' => $company->id, 'warehouse_id' => $warehouseMain->id, 'product_id' => $prodCoffee->id, 'minimum_stock' => 20,
        ]);
        \App\Models\InventoryStock::create([
            'company_id' => $company->id, 'warehouse_id' => $warehouseMain->id, 'product_id' => $prodGlass->id, 'minimum_stock' => 50,
        ]);

        $inventoryService->setOpeningStock($warehouseMain, $prodCoffee, 120, 'Stock inicial inventario');
        $inventoryService->transferStock($warehouseMain, $warehouseBranch, $prodCoffee, 20, 'Abastecer sucursal');
        $inventoryService->setOpeningStock($warehouseMain, $prodGlass, 30, 'Stock inicial vasos térmicos');
        
        // --- SEED BILLING AND RECEIVABLES (~35% OVERDUE) ---
        // Overdue Invoice (~3500)
        $invOverdue = \App\Models\Invoice::create([
            'company_id' => $company->id, 'customer_id' => $cust1->id, 'number' => 'FAC-000001',
            'issue_date' => Carbon::now()->subDays(45), 'due_date' => Carbon::now()->subDays(15),
            'currency' => 'NIO', 'sale_type' => SaleType::CREDIT, 'status' => InvoiceStatus::CONFIRMED,
            'subtotal' => 3500, 'discount_total' => 0, 'tax_total' => 0, 'total' => 3500, 'paid_amount' => 0, 'balance' => 3500,
            'created_by' => $user->id,
        ]);
        $invOverdue->items()->create([
            'product_id' => $servAdmin->id, 'description' => 'Servicios', 'quantity' => 1, 'unit_price' => 3500, 'subtotal' => 3500, 'total' => 3500
        ]);
        \App\Models\AccountReceivable::create([
            'company_id' => $company->id, 'customer_id' => $cust1->id, 'invoice_id' => $invOverdue->id,
            'original_amount' => 3500, 'paid_amount' => 0, 'balance' => 3500,
            'issued_at' => Carbon::now()->subDays(45), 'due_date' => Carbon::now()->subDays(15), 'status' => ReceivableStatus::OVERDUE,
        ]);

        // Current Invoice (~6500)
        $invCurrent = \App\Models\Invoice::create([
            'company_id' => $company->id, 'customer_id' => $cust2->id, 'number' => 'FAC-000002',
            'issue_date' => Carbon::now()->subDays(5), 'due_date' => Carbon::now()->addDays(10),
            'currency' => 'NIO', 'sale_type' => SaleType::CREDIT, 'status' => InvoiceStatus::PARTIALLY_PAID,
            'subtotal' => 8500, 'discount_total' => 0, 'tax_total' => 0, 'total' => 8500, 'paid_amount' => 2000, 'balance' => 6500,
            'created_by' => $user->id,
        ]);
        $invCurrent->items()->create([
            'product_id' => $servAdmin->id, 'description' => 'Servicios', 'quantity' => 1, 'unit_price' => 8500, 'subtotal' => 8500, 'total' => 8500
        ]);
        $arCurrent = \App\Models\AccountReceivable::create([
            'company_id' => $company->id, 'customer_id' => $cust2->id, 'invoice_id' => $invCurrent->id,
            'original_amount' => 8500, 'paid_amount' => 2000, 'balance' => 6500,
            'issued_at' => Carbon::now()->subDays(5), 'due_date' => Carbon::now()->addDays(10), 'status' => ReceivableStatus::PARTIAL,
        ]);
        \App\Models\ReceivablePayment::create([
            'company_id' => $company->id, 'account_receivable_id' => $arCurrent->id, 'amount' => 2000,
            'payment_date' => Carbon::now()->subDays(1), 'created_by' => $user->id
        ]);

        // --- SEED PURCHASES AND PAYABLES ---
        $supplier1 = \App\Models\Supplier::first();

        // Draft Purchase
        $purDraft = \App\Models\Purchase::create([
            'company_id' => $company->id, 'supplier_id' => $supplier1->id, 'number' => null,
            'purchase_date' => Carbon::now(), 'currency' => 'NIO', 'purchase_type' => \App\Enums\PurchaseType::CREDIT,
            'status' => \App\Enums\PurchaseStatus::DRAFT, 'subtotal' => 1000, 'total' => 1000, 'balance' => 1000,
            'created_by' => $user->id,
        ]);
        $purDraft->items()->create([
            'product_id' => $prodCoffee->id, 'description' => 'Café molido 400 g', 'quantity' => 10, 'unit_cost' => 100, 'subtotal' => 1000, 'total' => 1000
        ]);

        // Confirmed Credit Purchase (Creates CxP)
        $purCredit = \App\Models\Purchase::create([
            'company_id' => $company->id, 'supplier_id' => $supplier1->id, 'number' => 'COM-000001',
            'purchase_date' => Carbon::now()->subDays(10), 'due_date' => Carbon::now()->addDays(5),
            'currency' => 'NIO', 'purchase_type' => \App\Enums\PurchaseType::CREDIT,
            'status' => \App\Enums\PurchaseStatus::CONFIRMED, 'subtotal' => 5000, 'total' => 5000, 'balance' => 5000,
            'created_by' => $user->id, 'warehouse_id' => $warehouseMain->id,
        ]);
        $purCredit->items()->create([
            'product_id' => $prodCoffee->id, 'description' => 'Café', 'quantity' => 50, 'unit_cost' => 100, 'subtotal' => 5000, 'total' => 5000
        ]);
        \App\Models\AccountPayable::create([
            'company_id' => $company->id, 'supplier_id' => $supplier1->id, 'purchase_id' => $purCredit->id,
            'original_amount' => 5000, 'paid_amount' => 0, 'balance' => 5000,
            'issued_at' => Carbon::now()->subDays(10), 'due_date' => Carbon::now()->addDays(5), 'status' => \App\Enums\PayableStatus::PENDING,
        ]);

        // --- SEED TREASURY ---
        $treasuryService = app(\App\Services\Treasury\TreasuryService::class);
        $accountMain = \App\Models\FinancialAccount::create([
            'company_id' => $company->id,
            'name' => 'Caja Principal',
            'type' => \App\Enums\FinancialAccountType::CASH,
            'currency' => 'NIO',
            'active' => true,
            'is_default' => true,
        ]);
        $treasuryService->recordMovement($company->id, $accountMain->id, \App\Enums\FinancialMovementType::OPENING, 5000, 'Saldo Inicial');

        $accountBank = \App\Models\FinancialAccount::create([
            'company_id' => $company->id,
            'name' => 'BAC Córdobas',
            'type' => \App\Enums\FinancialAccountType::BANK,
            'currency' => 'NIO',
            'active' => true,
            'is_default' => false,
        ]);
        $treasuryService->recordMovement($company->id, $accountBank->id, \App\Enums\FinancialMovementType::OPENING, 20000, 'Saldo Inicial Bancario');
    }
}
