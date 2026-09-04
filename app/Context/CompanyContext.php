<?php

namespace App\Context;

use App\Models\Company;
use Illuminate\Support\Facades\Session;

class CompanyContext
{
    protected ?Company $company = null;

    public function setCompany(Company $company): void
    {
        $this->company = $company;
        Session::put('active_company_id', $company->id);
    }

    public function getCompany(): ?Company
    {
        if ($this->company) {
            return $this->company;
        }

        if (Session::has('active_company_id')) {
            $this->company = Company::find(Session::get('active_company_id'));
        }

        return $this->company;
    }

    public function getCompanyId(): ?int
    {
        return $this->getCompany()?->id;
    }
}
