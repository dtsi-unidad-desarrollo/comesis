<?php

namespace App\Http\Middleware;

use App\Models\Atm;
use App\Models\Helpers;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class HandleAtm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $date = Carbon::now();

        /** obtenemos el servicio actual activo por medio de la hora */
        $servicio = Helpers::getServicio($date);

        if (!$servicio) {
            Atm::where('en_uso', true)->update([
                'en_uso' => false,
                'user_id' => null,
                'last_active_at' => null,
            ]);
            session()->forget('selectedAtm');
        } else {
            // Auto-liberar taquillas huérfanas inactivas por más de 20 minutos
            Atm::where('en_uso', true)
                ->whereNotNull('last_active_at')
                ->where('last_active_at', '<', Carbon::now()->subMinutes(20))
                ->update([
                    'en_uso' => false,
                    'user_id' => null,
                    'last_active_at' => null,
                ]);

            // Si la sesión actual tiene un ATM seleccionado, refrescar su actividad
            if (session()->has('selectedAtm.id')) {
                $atmId = session('selectedAtm.id');
                $atm = Atm::find($atmId);
                if ($atm && $atm->en_uso) {
                    $atm->touchActivity();
                } else {
                    session()->forget('selectedAtm');
                }
            }
        }

        return $next($request);
    }
}
