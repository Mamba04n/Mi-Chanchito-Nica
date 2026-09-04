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
    }
}
