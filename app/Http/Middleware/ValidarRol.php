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
       
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        if ($user->rol != 1) {
            if ($user->rol) {
                // Obtener permisos de la sesión si existen, o cargarlos y cachearlos en la sesión
                $permisosMap = session('user_permisos_map');

                if (is_null($permisosMap)) {
                    $role = Role::find($user->rol);
                    $permisos = [];
                    if ($role) {
                        $permisos = $role->permisos()->pluck('nombre')->toArray();
                    }
                    $permisosMap = array_flip($permisos);
                    session([
                        'user_permisos' => $permisos,
                        'user_permisos_map' => $permisosMap,
                    ]);
                }

                $path = $request->path();
                $segments = explode('/', $path);
                $first = $segments[0] ?? '';
                $pathBase = explode('-', $first)[0]; // reportes-semanal -> reportes

                if ($request->route() && in_array($request->route()->getName(), ['admin.perfil.index', 'admin.perfil.update'])) {
                    return $next($request);
                }

                if ($request->route() && $request->route()->getName() === 'admin.auditoria.index' && $user->isRoot()) {
                    return $next($request);
                }

                if (!isset($permisosMap[$pathBase])) {
                    // Evitar bucle si ya está en recepcion
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
