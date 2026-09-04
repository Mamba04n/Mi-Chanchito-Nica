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
            ModuleSeeder::class,
        ]);

        $user = User::factory()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@chanchitonica.com',
            'password' => Hash::make('password'),
        ]);

        $company = Company::create([
            'name' => 'Pulpería El Progreso',
            'country_code' => 'NI',
            'currency_code' => 'NIO',
            'timezone' => 'America/Managua',
        ]);

        $user->companies()->attach($company->id, [
            'role_id' => 'admin',
            'status' => 'active',
        ]);

        $moduleManager = app(ModuleManager::class);
        $moduleManager->activateModule($company, 'sales');
        $moduleManager->activateModule($company, 'inventory');
        $moduleManager->activateModule($company, 'receivables');
        $moduleManager->activateModule($company, 'cash');
    }
}
