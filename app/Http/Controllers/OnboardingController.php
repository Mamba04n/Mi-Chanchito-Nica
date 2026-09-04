<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Services\ModuleManager;
use Illuminate\Support\Facades\Auth;
use App\Context\CompanyContext;

class OnboardingController extends Controller
{
    public function start()
    {
        // If user already has a company, redirect to dashboard
        if (Auth::user()->companies()->count() > 0) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.start');
    }

    public function store(Request $request, ModuleManager $moduleManager, CompanyContext $context)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'modules' => 'nullable|array',
            'modules.*' => 'string|exists:modules,key',
        ]);

        $company = Company::create([
            'name' => $request->name,
            'country_code' => 'NI',
            'currency_code' => 'NIO',
            'timezone' => 'America/Managua',
        ]);

        Auth::user()->companies()->attach($company->id, [
            'role_id' => 'admin',
            'status' => 'active',
        ]);

        if ($request->has('modules')) {
            foreach ($request->modules as $moduleKey) {
                // Ignore missing dependencies for now, or handle them gracefully
                try {
                    $moduleManager->activateModule($company, $moduleKey);
                } catch (\Exception $e) {
                    // Log or handle
                }
            }
        }

        $context->setCompany($company);

        return redirect()->route('dashboard')->with('success', 'Empresa creada exitosamente.');
    }
}
