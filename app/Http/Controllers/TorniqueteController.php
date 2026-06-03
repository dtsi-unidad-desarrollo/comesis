<?php

namespace App\Http\Controllers;

use App\Models\Torniquete;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TorniqueteController extends Controller
{
    public function index(Request $request)
    {
        $torniquetes = Torniquete::orderBy('id', 'desc')->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($torniquetes);
        }

        return view('admin.torniquetes.index', compact('torniquetes'));
    }

    public function create()
    {
        return view('admin.torniquetes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'endpoint_url' => 'nullable|url',
            'tipo' => 'nullable|string|max:255',
            'estatus' => 'nullable|in:0,1',
            'descripcion' => 'nullable|string',
        ]);

        $torniquete = Torniquete::create($data);

        if ($request->wantsJson()) {
            return response()->json($torniquete, Response::HTTP_CREATED);
        }

        return redirect()->route('admin.torniquetes.index')->with('mensaje', 'Torniquete creado correctamente');
    }

    public function show(Request $request, Torniquete $torniquete)
    {
        if ($request->wantsJson()) {
            return response()->json($torniquete);
        }

        return view('admin.torniquetes.show', compact('torniquete'));
    }

    public function edit(Torniquete $torniquete)
    {
        return view('admin.torniquetes.edit', compact('torniquete'));
    }

    public function update(Request $request, Torniquete $torniquete)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'endpoint_url' => 'nullable|url',
            'tipo' => 'nullable|string|max:255',
            'estatus' => 'nullable|in:0,1',
            'descripcion' => 'nullable|string',
        ]);

        $torniquete->update($data);

        if ($request->wantsJson()) {
            return response()->json($torniquete);
        }

        return redirect()->route('admin.torniquetes.index')->with('mensaje', 'Torniquete actualizado correctamente');
    }

    public function destroy(Request $request, Torniquete $torniquete)
    {
        $torniquete->delete();

        if ($request->wantsJson()) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return redirect()->route('admin.torniquetes.index')->with('mensaje', 'Torniquete eliminado correctamente');
    }
}
