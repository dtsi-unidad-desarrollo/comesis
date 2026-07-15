<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permiso;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permisos')->orderBy('id', 'desc')->paginate(20);
        $permisos = Permiso::where('estatus', 1)->orderBy('nombre')->get();
        return view('admin.roles.index', compact('roles', 'permisos'));
    }

    public function create()
    {
        return redirect()->route('admin.roles.index');
    }

    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        $role = Role::create($data);

        if ($request->filled('permisos')) {
            $role->permisos()->sync($request->input('permisos'));
        }

        AuditLogger::log('create_role', 'role', $role->id, [
            'message' => 'Se creó un rol',
            'nombre' => $role->nombre,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol creado correctamente.')
            ->with('estatus', 200);
    }

    public function show(Role $role)
    {
        return redirect()->route('admin.roles.edit', $role->id);
    }

    public function edit(Role $role)
    {
        $permisos = Permiso::orderBy('nombre')->get();
        $role->load('permisos');
        return view('admin.roles.edit', compact('role', 'permisos'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = $request->validated();
        $role->update($data);

        $permisos = $request->input('permisos', []);
        $role->permisos()->sync($permisos);

        AuditLogger::log('update_role', 'role', $role->id, [
            'message' => 'Se actualizó un rol',
            'nombre' => $role->nombre,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol actualizado correctamente.')
            ->with('estatus', 200);
    }

    public function destroy(Role $role)
    {
        DB::table('rol_permisos')->where('id_rol', $role->id)->delete();
        AuditLogger::log('delete_role', 'role', $role->id, [
            'message' => 'Se eliminó un rol',
            'nombre' => $role->nombre,
        ]);
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol eliminado correctamente.')
            ->with('estatus', 200);
    }
}
