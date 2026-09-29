<?php

namespace App\Http\Controllers;

use App\Models\Apoyo;
use App\Models\Atm;
use App\Models\Comensale;
use App\Models\DataDev;
use App\Models\Entrada;
use App\Models\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RecepcionController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev();
    }

    /**
     * Vista principal de recepción de comensales y apoyos.
     */
    public function index(Request $request)
    {
        $respuesta = $this->data->respuesta;

        try {
            $comensal = null;
            $mensaje_comensal = '';
            $mensaje_torniquete = 'Cerrado';
            $date = Carbon::now();
            $cantidadDeEntradas = 0;

            $servicio = Helpers::getServicio($date);
            if ($servicio) {
                $cantidadDeEntradas = Helpers::getTotalEntradas($date->format('Y-m-d'), $servicio->nombre);
            }

            $atms = Atm::with('torniquete')->where('en_uso', false)->get();
            $user = Auth::user();
            $selectedAtm = session('selectedAtm');

            if ($request->filled('cedula')) {
                $resultado = $this->procesarIngreso(
                    $request->cedula,
                    $servicio,
                    $selectedAtm,
                    $user,
                    $date,
                    $cantidadDeEntradas
                );

                if ($resultado instanceof \Illuminate\Http\RedirectResponse) {
                    return $resultado;
                }

                $comensal = $resultado['comensal'] ?? null;
                $mensaje_comensal = $resultado['mensaje_comensal'] ?? '';
                $mensaje_torniquete = $resultado['mensaje_torniquete'] ?? 'Cerrado';
                $cantidadDeEntradas = $resultado['cantidadDeEntradas'] ?? $cantidadDeEntradas;
            }

            return view('admin.recepcion.index', compact(
                'comensal',
                'respuesta',
                'mensaje_comensal',
                'mensaje_torniquete',
                'cantidadDeEntradas',
                'servicio',
                'request',
                'atms',
                'selectedAtm'
            ));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar consultar el comensal!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Valida el servicio y distribuye el flujo entre Apoyo y Comensal regular.
     */
    private function procesarIngreso($identificador, $servicio, $selectedAtm, $user, Carbon $date, int $cantidadDeEntradas)
    {
        if (!$servicio) {
            Atm::where('en_uso', true)->update(['en_uso' => false]);
            return back()->with([
                'mensaje' => 'Comedor inactivo, está fuera del horario de servicio.',
                'estatus' => Response::HTTP_UNAUTHORIZED
            ]);
        }

        if ($cantidadDeEntradas >= $servicio->disponibilidad) {
            return back()->with([
                'mensaje' => '¡Bandejas agotadas!',
                'estatus' => Response::HTTP_UNAUTHORIZED
            ]);
        }

        // 1. Verificar si el código ingresado corresponde a un Apoyo Institucional
        $apoyo = Apoyo::where('codigo', $identificador)->first();
        if ($apoyo) {
            return $this->procesarApoyo($apoyo, $servicio, $selectedAtm, $user, $date);
        }

        // 2. Verificar si corresponde a un Comensal regular
        $comensal = Comensale::where('cedula', $identificador)->first();
        if (!$comensal) {
            return [
                'comensal' => null,
                'mensaje_comensal' => '<strong>¡NO PERMITIR EL ACCESO!</strong> Comensal no registrado o código de apoyo inexistente.',
                'mensaje_torniquete' => 'Cerrado',
                'cantidadDeEntradas' => $cantidadDeEntradas,
            ];
        }

        return $this->procesarComensal($comensal, $servicio, $selectedAtm, $user, $date);
    }

    /**
     * Procesa y valida el acceso de un Apoyo Institucional.
     */
    private function procesarApoyo(Apoyo $apoyo, $servicio, $selectedAtm, $user, Carbon $date)
    {
        if (!$apoyo->estatus) {
            return back()->with([
                'mensaje' => "<strong>¡NO PERMITIR EL ACCESO!</strong> El código de apoyo {$apoyo->codigo} ({$apoyo->institucion}) está inactivo.",
                'estatus' => Response::HTTP_UNAUTHORIZED
            ]);
        }

        if ($apoyo->cantidad_consumida >= $apoyo->cantidad_habilitada) {
            return back()->with([
                'mensaje' => "<strong>¡NO PERMITIR EL ACCESO!</strong> El apoyo institucional {$apoyo->institucion} ya ha consumido todas sus comidas habilitadas ({$apoyo->cantidad_habilitada}/{$apoyo->cantidad_habilitada}).",
                'estatus' => Response::HTTP_UNAUTHORIZED
            ]);
        }

        // Incrementar comidas consumidas
        $apoyo->increment('cantidad_consumida');

        // Registrar entrada
        Entrada::create([
            'cedula' => $apoyo->codigo,
            'nombres' => $apoyo->institucion,
            'apellidos' => 'APOYO INSTITUCIONAL',
            'nacionalidad' => 'V',
            'sexo' => 'N/A',
            'tipo_comensal' => 'APOYO',
            'comida' => $servicio->nombre,
            'sede' => 'CENTRAL',
            'fecha' => $date->format('Y-m-d'),
            'hora' => $date->format('h:ia'),
            'atm_id' => $selectedAtm->id ?? null,
            'allowed_by_user_id' => $user->id ?? null,
        ]);

        $mensaje_torniquete = $this->abrirTorniquete($selectedAtm, $apoyo->codigo, $apoyo->institucion);
        $cantidadDeEntradas = Helpers::getTotalEntradas($date->format('Y-m-d'), $servicio->nombre);
        $restantes = $apoyo->cantidad_habilitada - $apoyo->cantidad_consumida;

        // Adaptar objeto para vista de recepción
        $comensalObj = (object)[
            'nombres' => $apoyo->institucion,
            'apellidos' => 'APOYO INSTITUCIONAL',
            'sexo' => 'N/A',
            'tipo_comensal' => 'APOYO',
            'sede' => 'INSTITUCIÓN EXTERNA',
            'direccion' => "Comidas consumidas: {$apoyo->cantidad_consumida} de {$apoyo->cantidad_habilitada} (Restantes: {$restantes})",
            'nacionalidad' => '',
            'cedula' => $apoyo->codigo,
            'foto' => asset('assets/img/logo.png'),
        ];

        return [
            'comensal' => $comensalObj,
            'mensaje_comensal' => '',
            'mensaje_torniquete' => $mensaje_torniquete,
            'cantidadDeEntradas' => $cantidadDeEntradas,
        ];
    }

    /**
     * Procesa y valida el acceso de un Comensal regular.
     */
    private function procesarComensal(Comensale $comensal, $servicio, $selectedAtm, $user, Carbon $date)
    {
        if ($comensal->estatus == 0) {
            return back()->with([
                'mensaje' => "<strong>¡NO PERMITIR EL ACCESO!</strong> El comensal {$comensal->nombres} {$comensal->apellidos} está inactivo, no puede ingresar.",
                'estatus' => Response::HTTP_UNAUTHORIZED
            ]);
        }

        // Validar si ya consumió en el servicio del día
        $entradas = Entrada::where([
            'cedula' => $comensal->cedula,
            'fecha' => $date->format('Y-m-d'),
            'comida' => $servicio->nombre,
        ])->get();

        if ($entradas->isNotEmpty()) {
            return [
                'comensal' => null,
                'mensaje_comensal' => "<strong>¡NO PERMITIR EL ACCESO!</strong><br>" .
                    "El comensal: {$comensal->nombres} {$comensal->apellidos}<br>" .
                    "Cédula: {$comensal->cedula}<br>" .
                    "Ya consumió el servicio: {$servicio->nombre}<br>" .
                    "Hora de entrada: " . $entradas->first()->hora . '.',
                'mensaje_torniquete' => 'Cerrado',
                'cantidadDeEntradas' => Helpers::getTotalEntradas($date->format('Y-m-d'), $servicio->nombre),
            ];
        }

        $comensal->carreras = $comensal->carreras ?? [];
        Helpers::setEntradaComedor($comensal, $servicio, $selectedAtm, $user);

        $mensaje_torniquete = $this->abrirTorniquete(
            $selectedAtm,
            $comensal->cedula,
            "{$comensal->nombres} {$comensal->apellidos}"
        );

        $cantidadDeEntradas = Helpers::getTotalEntradas($date->format('Y-m-d'), $servicio->nombre);

        if (empty($comensal->foto)) {
            $comensal->foto = asset('assets/img/avatar.png');
        }

        return [
            'comensal' => $comensal,
            'mensaje_comensal' => '',
            'mensaje_torniquete' => $mensaje_torniquete,
            'cantidadDeEntradas' => $cantidadDeEntradas,
        ];
    }

    /**
     * Envía comando de apertura al torniquete asociado.
     */
    private function abrirTorniquete($selectedAtm, string $id, string $nombre): string
    {
        if (!$selectedAtm) {
            return ' No se ha seleccionado una ATM/torniquete en recepción.';
        }

        $openResp = Helpers::openAtmDoor($selectedAtm, [
            'id' => $id,
            'name' => $nombre,
            'allowed' => true,
        ]);

        if (!empty($openResp['ok']) && $openResp['ok']) {
            return ' Comando de apertura enviado al ' . ($selectedAtm->torniquete->nombre ?? 'Torniquete') . '.';
        }

        return ' No se pudo enviar comando al torniquete: ' . ($openResp['message'] ?? json_encode($openResp));
    }

    /**
     * Maneja la selección y liberación de ATM/Torniquetes.
     */
    public function selectAtm(Request $request)
    {
        if ($request->input('change', false) === 'true') {
            Atm::where('id', session('selectedAtm.id'))->update(['en_uso' => false]);
            session()->forget('selectedAtm');
            return back()->with([
                'mensaje' => 'ATM/Torniquete deseleccionado.',
                'estatus' => Response::HTTP_OK
            ]);
        }

        $selectedAtm = Atm::with('torniquete')->find($request->atm_id);

        if (!$selectedAtm) {
            return back()->with('error', 'ATM no encontrada');
        }

        if ($selectedAtm->en_uso) {
            return back()->with([
                'mensaje' => "ATM/Torniquete {$selectedAtm->nombre} ya está en uso. Por favor seleccione otro.",
                'estatus' => Response::HTTP_NOT_FOUND
            ]);
        }

        $servicio = Helpers::getServicio(Carbon::now());
        if (!$servicio) {
            return back()->with([
                'mensaje' => 'Comedor inactivo, está fuera del horario de servicio. No se puede seleccionar un ATM/Torniquete.',
                'estatus' => Response::HTTP_UNAUTHORIZED
            ]);
        }

        $selectedAtm->update(['en_uso' => true]);
        session(['selectedAtm' => $selectedAtm]);

        return back()->with([
            'mensaje' => 'ATM/Torniquete seleccionado: ' . $selectedAtm->nombre . ' - ' . ($selectedAtm->torniquete->nombre ?? 'Sin torniquete asociado'),
            'estatus' => Response::HTTP_OK
        ]);
    }
}
