<?php

namespace App\Http\Controllers;

use App\Models\Atm;
use App\Models\Torniquete;
use App\Http\Requests\StoreAtmRequest;
use App\Http\Requests\UpdateAtmRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http as HttpClient;
use Illuminate\Http\Response;

class AtmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $atms = Atm::with('torniquete')->orderBy('id','desc')->paginate(20);
        if (request()->wantsJson()) return response()->json($atms);
        return view('admin.atm.index', compact('atms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $torniquetes = Torniquete::all();
        return view('admin.atm.create', compact('torniquetes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAtmRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAtmRequest $request)
    {
        $data = $request->validated();
        $atm = Atm::create($data);
        if ($request->wantsJson()) {
            return response()->json($atm, Response::HTTP_CREATED);
        }
        return redirect()->route('admin.atms.index')->with('mensaje', 'ATM creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Atm  $atm
     * @return \Illuminate\Http\Response
     */
    public function show(Atm $atm)
    {
        $atm->load('torniquete');
        if (request()->wantsJson()) return response()->json($atm);
        return view('admin.atm.show', compact('atm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Atm  $atm
     * @return \Illuminate\Http\Response
     */
    public function edit(Atm $atm)
    {
        $torniquetes = Torniquete::all();
        return view('admin.atm.edit', compact('atm','torniquetes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAtmRequest  $request
     * @param  \App\Models\Atm  $atm
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAtmRequest $request, Atm $atm)
    {
        $atm->update($request->validated());
        if ($request->wantsJson()) return response()->json($atm);
        return redirect()->route('admin.atms.index')->with('mensaje', 'ATM actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Atm  $atm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Atm $atm)
    {
        $atm->delete();
        if (request()->wantsJson()) return response()->json(null, Response::HTTP_NO_CONTENT);
        return redirect()->route('admin.atms.index')->with('mensaje', 'ATM eliminado');
    }

    /**
     * Send open command to the torniquete assigned to this ATM.
     */
    public function open(\Illuminate\Http\Request $request, Atm $atm)
    {
        if (!$atm->torniquete) {
            return response()->json(['message' => 'No torniquete assigned'], Response::HTTP_BAD_REQUEST);
        }

        $endpoint = $atm->torniquete->endpoint_url;
        if (!$endpoint) {
            return response()->json(['message' => 'Torniquete has no endpoint configured'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|string',
                'name' => 'required|string',
                'allowed' => 'sometimes|boolean',
            ]);

            $payload = [
                'id' => $validated['id'],
                'name' => $validated['name'],
                'allowed' => $request->input('allowed', true),
                'doorId' => $atm->torniquete->id ?? $atm->id,
            ];

            $resp = HttpClient::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            return response()->json(['status' => 'ok', 'torniquete_response' => $resp->body()]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error sending open command', 'error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
