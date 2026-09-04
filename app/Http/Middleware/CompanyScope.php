<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Context\CompanyContext;
use Illuminate\Support\Facades\Auth;

class CompanyScope
{
    public function __construct(protected CompanyContext $companyContext)
    {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            $company = $this->companyContext->getCompany();

            if (!$company && $user->companies()->count() > 0) {
                // If no company selected in context, select the first one.
                $company = $user->companies()->first();
                $this->companyContext->setCompany($company);
            }

            if ($company) {
                // Ensure the user actually belongs to this company
                if (!$user->companies()->where('companies.id', $company->id)->exists()) {
                    abort(403, 'Unauthorized access to company.');
                }
            } else {
                // If user has no company, they might need to go to onboarding
                if (!$request->routeIs('onboarding.*') && !$request->routeIs('logout')) {
                    return redirect()->route('onboarding.start');
                }
            }
        }

        return $next($request);
    }
}
