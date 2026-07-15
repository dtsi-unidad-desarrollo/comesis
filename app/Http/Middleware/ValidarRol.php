<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RolPermiso;
use App\Models\Role;
use App\Models\Permiso;
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
                $role = Role::find(Auth::user()->rol);

                $permisos = [];
                if ($role) {
                    $permisos = $role->permisos()->pluck('nombre')->toArray();
                }

                $path = $request->path();
                $segments = explode('/', $path);
                $first = $segments[0] ?? '';
                $pathBase = explode('-', $first)[0]; // reportes-semanal -> reportes

                if ($request->route() && in_array($request->route()->getName(), ['admin.perfil.index', 'admin.perfil.update'])) {
                    return $next($request);
                }

                if ($request->route() && $request->route()->getName() === 'admin.auditoria.index' && Auth::user()->rol == 1) {
                    return $next($request);
                }

                if (!in_array($pathBase, $permisos)) {
                    // If the user is already on recepcion, avoid redirect loop
                    if ($request->route() && $request->route()->getName() === 'admin.recepcion.index') {
                        return $next($request);
                    }

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
