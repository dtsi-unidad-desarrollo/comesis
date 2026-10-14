<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditLogger
{
    public static function log(string $action, ?string $entityType = null, ?int $entityId = null, ?array $details = null): void
    {
        $user = Auth::user();
        $request = app(Request::class);

        try {
            AuditLog::create([
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? $user->nombre : ($details['attempted_email'] ?? $details['email'] ?? 'Invitado / Anónimo'),
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'details' => $details ? json_encode($details) : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
