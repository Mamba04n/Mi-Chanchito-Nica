<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_company_is_redirected_to_onboarding()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('onboarding.start'));
    }

    public function test_user_can_create_company_and_is_redirected_to_dashboard()
    {
        $user = User::factory()->create();
        
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->seed(\Database\Seeders\ModuleSeeder::class);

        $response = $this->actingAs($user)->post(route('onboarding.store'), [
            'name' => 'Mi Nueva Empresa',
            'modules' => ['customers', 'billing'],
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('companies', ['name' => 'Mi Nueva Empresa']);
        
        $company = Company::where('name', 'Mi Nueva Empresa')->first();
        $this->assertTrue($user->companies->contains($company));
        
        // Assert modules are active
        $this->assertDatabaseHas('company_modules', [
            'company_id' => $company->id,
            'module_id' => \App\Models\Module::where('key', 'customers')->first()->id,
            'disabled_at' => null,
        ]);
    }
}
