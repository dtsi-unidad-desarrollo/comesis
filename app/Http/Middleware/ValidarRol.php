<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RolPermiso;
use Illuminate\Http\Response;

class ValidarRol
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
        if (Auth::user()->rol != 1) {
            if (Auth::user()->rol) {
                $rolPermisos = RolPermiso::select('id_permiso')->where('id_rol', Auth::user()->rol)->get();

                $permisos = [];
                foreach ($rolPermisos as $key => $value) {
                    array_push($permisos, $value->id_permiso);
                }

                $path = explode('/', $request->path())[0];
                $pathBase = explode('-', $path)[0]; // reportes-semanal -> reportes

                if (!in_array($pathBase, $permisos)) {
                    return redirect()->route('admin.recepcion.index')->with([
                        "mensaje" => "No tiene autorización para acceder al modulo: " . $path,
                        "estatus" => Response::HTTP_UNAUTHORIZED
                    ]);
                }
            }
        }

        return $next($request);
    }
}
