<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permiso;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
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

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol actualizado correctamente.')
            ->with('estatus', 200);
    }

    public function destroy(Role $role)
    {
        DB::table('rol_permisos')->where('id_rol', $role->id)->delete();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol eliminado correctamente.')
            ->with('estatus', 200);
    }
}
