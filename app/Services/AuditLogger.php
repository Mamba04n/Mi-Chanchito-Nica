<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(string $action, ?int $companyId = null, ?string $entityType = null, ?string $entityId = null, array $metadata = []): void
    {
        AuditLog::create([
            'company_id' => $companyId,
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metadata' => empty($metadata) ? null : $metadata,
            'ip_address' => request()->ip(),
        ]);
    }
}
