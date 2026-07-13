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
            Atm::where('en_uso', true)->update(['en_uso' => false]);
            session()->forget('selectedAtm');
        }

        return $next($request);
    }
}
