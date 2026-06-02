<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Http\Requests\StorePermisoRequest;
use App\Http\Requests\UpdatePermisoRequest;
use Illuminate\Support\Facades\DB;

class PermisoController extends Controller
{
    public function index()
    {
        $permisos = Permiso::orderBy('id', 'desc')->paginate(20);
        return view('admin.permisos.index', compact('permisos'));
    }

    public function create()
    {
        return redirect()->route('admin.permisos.index');
    }

    public function store(StorePermisoRequest $request)
    {
        Permiso::create($request->validated());
        return redirect()->route('admin.permisos.index')->with('mensaje', 'Permiso creado correctamente.');
    }

    public function show(Permiso $permiso)
    {
        return redirect()->route('admin.permisos.edit', $permiso->id);
    }

    public function edit(Permiso $permiso)
    {
        return view('admin.permisos.edit', compact('permiso'));
    }

    public function update(UpdatePermisoRequest $request, Permiso $permiso)
    {
        $permiso->update($request->validated());
        return redirect()->route('admin.permisos.index')->with('mensaje', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permiso $permiso)
    {
        DB::table('rol_permisos')->where('id_permiso', $permiso->id)->delete();
        $permiso->delete();
        return redirect()->route('admin.permisos.index')->with('mensaje', 'Permiso eliminado correctamente.');
    }
}
