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
        $query = Role::with('permisos')->orderBy('id', 'desc');

        // Si el usuario autenticado no es ROOT, ocultar el rol ROOT
        if (!auth()->check() || !auth()->user()->isRoot()) {
            $query->where('id', '!=', 1)->whereRaw('UPPER(nombre) != ?', ['ROOT']);
        }

        $roles = $query->paginate(20);
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

        // Evitar que usuarios que no sean ROOT intenten crear un rol llamado ROOT
        if ((!auth()->check() || !auth()->user()->isRoot()) && strtoupper(trim($data['nombre'] ?? '')) === 'ROOT') {
            abort(403, 'No tienes permisos para crear un rol ROOT.');
        }

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
        // Si no es ROOT, no puede ver ni editar el rol ROOT
        if (($role->id === 1 || strtoupper($role->nombre) === 'ROOT') && (!auth()->check() || !auth()->user()->isRoot())) {
            abort(403, 'No tienes permisos para editar el rol ROOT.');
        }

        $permisos = Permiso::orderBy('nombre')->get();
        $role->load('permisos');
        return view('admin.roles.edit', compact('role', 'permisos'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        // Si no es ROOT, no puede modificar el rol ROOT
        if (($role->id === 1 || strtoupper($role->nombre) === 'ROOT') && (!auth()->check() || !auth()->user()->isRoot())) {
            abort(403, 'No tienes permisos para modificar el rol ROOT.');
        }

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
        // El rol ROOT jamás puede eliminarse
        if ($role->id === 1 || strtoupper($role->nombre) === 'ROOT') {
            abort(403, 'El rol ROOT no puede ser eliminado.');
        }

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
