<?php

namespace App\Traits;

use App\Models\Company;
use App\Context\CompanyContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::addGlobalScope('company_isolation', function (Builder $builder) {
            $companyId = app(CompanyContext::class)->getCompanyId();
            if ($companyId) {
                $builder->where($builder->getQuery()->from . '.company_id', $companyId);
            }
        });

        static::creating(function ($model) {
            if (!$model->company_id) {
                $companyId = app(CompanyContext::class)->getCompanyId();
                if ($companyId) {
                    $model->company_id = $companyId;
                }
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
