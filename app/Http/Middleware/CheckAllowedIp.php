<?php

namespace App\Http\Middleware;

use App\Models\AllowedIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAllowedIp
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('local', 'testing')) {
            return $next($request);
        }

        $ip = $request->ip();
        $allowed = AllowedIp::where('status', true)->where('ip_address', $ip)->exists();

        if (! $allowed) {
            abort(403, 'Tu IP no está autorizada para acceder a este sistema.');
        }

        return $next($request);
    }
}
