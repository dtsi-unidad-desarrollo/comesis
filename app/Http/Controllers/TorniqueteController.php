<?php

namespace App\Http\Controllers;

use App\Models\Torniquete;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TorniqueteController extends Controller
{
    public function index()
    {
        return response()->json(Torniquete::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'endpoint_url' => 'nullable|url',
            'tipo' => 'nullable|string',
            'estatus' => 'nullable|string',
            'descripcion' => 'nullable|string',
        ]);

        $t = Torniquete::create($data);
        return response()->json($t, Response::HTTP_CREATED);
    }

    public function show(Torniquete $torniquete)
    {
        return response()->json($torniquete);
    }

    public function update(Request $request, Torniquete $torniquete)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'endpoint_url' => 'nullable|url',
            'tipo' => 'nullable|string',
            'estatus' => 'nullable|string',
            'descripcion' => 'nullable|string',
        ]);

        $torniquete->update($data);
        return response()->json($torniquete);
    }

    public function destroy(Torniquete $torniquete)
    {
        $torniquete->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
