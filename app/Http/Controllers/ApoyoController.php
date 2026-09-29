<?php

namespace App\Http\Controllers;

use App\Models\Apoyo;
use App\Models\DataDev;
use App\Models\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApoyoController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $apoyos = Apoyo::orderBy('id', 'DESC')->paginate(12);
            $respuesta = $this->data->respuesta;
            return view('admin.apoyos.index', compact('apoyos', 'respuesta'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar consultar apoyos!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $respuesta = $this->data->respuesta;
        return view('admin.apoyos.create', compact('respuesta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'institucion' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:apoyos,codigo',
            'cantidad_habilitada' => 'required|integer|min:1',
            'estatus' => 'required|boolean'
        ]);

        try {
            Apoyo::create([
                'institucion' => strtoupper($request->institucion),
                'codigo' => strtoupper($request->codigo),
                'cantidad_habilitada' => $request->cantidad_habilitada,
                'cantidad_consumida' => 0,
                'estatus' => $request->estatus,
            ]);

            $mensaje = "Apoyo institucional registrado correctamente.";
            $estatus = Response::HTTP_OK;
            return redirect()->route('admin.apoyos.index')->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error al crear apoyo!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->withInput()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apoyo  $apoyo
     * @return \Illuminate\Http\Response
     */
    public function edit(Apoyo $apoyo)
    {
        $respuesta = $this->data->respuesta;
        return view('admin.apoyos.edit', compact('apoyo', 'respuesta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apoyo  $apoyo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apoyo $apoyo)
    {
        $request->validate([
            'institucion' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:apoyos,codigo,' . $apoyo->id,
            'cantidad_habilitada' => 'required|integer|min:1',
            'estatus' => 'required|boolean'
        ]);

        try {
            $apoyo->update([
                'institucion' => strtoupper($request->institucion),
                'codigo' => strtoupper($request->codigo),
                'cantidad_habilitada' => $request->cantidad_habilitada,
                'estatus' => $request->estatus,
            ]);

            $mensaje = "Apoyo institucional actualizado correctamente.";
            $estatus = Response::HTTP_OK;
            return redirect()->route('admin.apoyos.index')->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error al actualizar apoyo!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->withInput()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apoyo  $apoyo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apoyo $apoyo)
    {
        try {
            $apoyo->delete();
            $mensaje = "Apoyo eliminado correctamente.";
            $estatus = Response::HTTP_OK;
            return redirect()->route('admin.apoyos.index')->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error al eliminar apoyo!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    public function printTicket(Apoyo $apoyo)
    {
        // Obtener logo en Base64 para evitar problemas de carga en DomPDF
        $logoPath = public_path('assets/img/logo-dtsi-unellez.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('assets/img/logo.png');
        }
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Obtener código QR en Base64
        $qrBase64 = '';
        try {
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode($apoyo->codigo);
            $ctx = stream_context_create([
                'http' => ['timeout' => 5],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
            ]);
            $qrData = @file_get_contents($qrUrl, false, $ctx);
            if ($qrData) {
                $qrBase64 = 'data:image/png;base64,' . base64_encode($qrData);
            }
        } catch (\Throwable $e) {
            $qrBase64 = '';
        }

        $cantidad = max(1, (int) $apoyo->cantidad_habilitada);
        $fechaEmision = Carbon::now()->format('d/m/Y h:i A');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.apoyos.ticket', compact('apoyo', 'logoBase64', 'qrBase64', 'cantidad', 'fechaEmision'))
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->stream('tickets_apoyo_' . $apoyo->codigo . '.pdf');
    }
}

