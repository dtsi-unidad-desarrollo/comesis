<?php

namespace App\Http\Controllers;

use App\Models\Comensale;
use App\Models\DataDev;
use App\Models\Entrada;
use App\Models\Estudiante;
use App\Models\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RecepcionController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }


    public function index(Request $request)
    {
        /** Variable global para los alertas */
        $respuesta =  $this->data->respuesta;

        try {

            /** se declaran las variables */
            $comensal = null;
            $mensaje_comensal = '';
            $date = Carbon::now();
            $entradas = null;
            $tipoComida = '';
            $estatusEstudiante =  0;
            $codigoCarrera = "";
            $cantidadDeEntradas = 0;

            /** obtenemos el servicio actual activo por medio de la hora */
            $servicio = Helpers::getServicio($date);

            /** Obtenemos el total de entradas */
            if ($servicio) $cantidadDeEntradas = Helpers::getTotalEntradas($date->format('d-m-Y'), $servicio->nombre);

            /** Se valida si hay una cedula  */
            if ($request->filled('cedula')) {
                return $comensal = $this->getEmpleados($request->cedula);
                /** Si no se detecta un servicio, el comedor esta fuera de servicio */
                if (!$servicio) {
                    $mensaje = "Comedor inactivo, está fuera del horario de servicio.";
                    $estatus = Response::HTTP_UNAUTHORIZED;
                    return back()->with(compact('mensaje', 'estatus'));
                } else {

                    /** Validamos la disponibilidad del servicio */
                    if ($cantidadDeEntradas >=  $servicio->disponibilidad) {
                        $mensaje = "¡Bandejas agotadas!";
                        $estatus = Response::HTTP_UNAUTHORIZED;
                        return back()->with(compact('mensaje', 'estatus'));
                    }

                    /** PRIMERO BUSCAMOS EN COMESIS */
                    $comensal = Comensale::where('cedula', $request->cedula)->first();

                    if (!$comensal) {
                        /** SEGUNDO CONSULTAMOS DUX  para los ESTUDIANTES */
                        $comensal = $this->getEstudiantes($request->cedula);
                    }

                    if (!$comensal) {
                        /** Buscamos en TEREPAIMA */
                        return $comensal = $this->getEmpleados($request->cedula);
                    }

                    /** Validamos si existe el comensal */
                    if ($comensal == null) {
                        /** MENSAJE DE FALLO BUSQUEDA DE COMENSALES */
                        $mensaje_comensal = "Comensal no registrado.";
                    } else {

                        /** Validamos si el comensal tiene estatus activo o no */
                        if ($comensal->estatus == 0) {
                            /** Se valdiad si el comensal esta activo en el sistema  */
                            $mensaje = "<strong> El comensal </strong>" . $comensal->nombres . " " . $comensal->apellidos . " está inactivo, no puede ingresar.";
                            $estatus = Response::HTTP_UNAUTHORIZED;
                            return back()->with(compact('mensaje', 'estatus'));
                        }

                        /** si el comensal es del sistema se le push un elemento ficticio */
                        if ($comensal->tipo !== 'ESTUDIANTE') {
                            $comensal->carreras  = [false];
                        }


                        /** Validamos si esta activo en una carrera de pregrago */
                        if (count($comensal->carreras)) {

                            /** obtenemos las entradas del dia del comensal  para validar que coma una ves por servicio */
                            $entradas = Entrada::where([
                                'cedula' => $comensal->cedula,
                                'fecha' =>  $date->format('d-m-Y'),
                                'comida' => $servicio->nombre // Aqui valida si es ALMUERZO | CENA
                            ])->get();


                            /** Validamos si ya comio y cual comida */
                            if (count($entradas)) {
                                $mensaje_comensal = "El comensal " . $comensal->nombres . " " . $comensal->apellidos . ", Cédula: " . $comensal->cedula . ", ya consumio el servicio: " . $servicio->nombre ?? '' . ". ";
                                $comensal = null;
                            } else {

                                /** Marcar entrada automaticamente */
                                Helpers::setEntradaComedor($comensal, $servicio);

                                /** obtener las entradas actualizadas */
                                $cantidadDeEntradas = Helpers::getTotalEntradas($date->format('d-m-Y'), $servicio->nombre ?? '');
                            }
                        } else {
                            /** Se valdiad si el comensal esta activo en el sistema  */
                            $mensaje = "<strong>¡Ya comió!</strong> El comensal " . $comensal->nombres . " " . $comensal->apellidos . " está inactivo, no puede ingresar.";
                            $estatus = Response::HTTP_NOT_FOUND;
                            return back()->with(compact('mensaje', 'estatus'));
                        }
                    }
                }
            }

            return view('admin.recepcion.index', compact('comensal', 'respuesta', 'mensaje_comensal', 'cantidadDeEntradas', 'servicio', 'request'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar consultar los comensal!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }


    public function getEstudiantes($cedula)
    {
        $comensal = DB::connection('mysql_second')->table('estudiantes')->where('Cedula', $cedula)
            ->select('nombres', 'apellidos', 'nacionalidad', 'Cedula as cedula', 'Sexo as sexo', 'Sede as sede')
            ->first();
        if ($comensal) {
            /** Se setea el tipo */
            $comensal->tipo = "ESTUDIANTE";

            /** Consultamos todas las carreras donde el estudiante este activo */
            $carreras = DB::connection('mysql_second')->table('carreras_est')

                ->join('carreras', 'carreras.CodCar', '=', 'carreras_est.CodCar')
                ->join('sede', 'sede.CodSede', '=', 'carreras_est.Sede')
                ->join('programas', 'programas.codPrograma', '=', 'carreras.codPrograma')

                ->where('carreras_est.ConexEst',  $comensal->cedula)
                ->where('carreras_est.Status', 'A')
                ->select(
                    'carreras_est.Status as estatus_estudiante', // si esta activo dentro de la carrera
                    'carreras_est.ConexEst as dni',
                    'carreras_est.CodCar as codigo_carrera',
                    'carreras_est.Sede as sede',

                    'carreras.codPrograma as codigo_programa',
                    'carreras.NombCar as nombre_carrera',
                    'carreras.Tipo as tipo_carrera',
                    'carreras.Status as estatus_carrera',

                    'programas.codPrograma',
                    'programas.nombre as nombre_programa',

                    'sede.CodSede as codigo_sede',
                    'sede.Sede as nombre_sede',
                    'sede.Tipo as tipo_sede',
                    'sede.TipoSede as tipo_oferta_sede',
                    'sede.Zona as zona_sede',
                    'sede.oestado as estado_sede',
                    'sede.Municipio as municipio_sede',
                    'sede.oparroquia as parroquia_sede',
                    'sede.osector as sector_sede',
                    'sede.Arse as arse',
                )
                ->get();




            $comensal->carreras =  $carreras;
            $comensal->estatus_estudiante =  count($carreras) ? $carreras[0]->estatus_estudiante : 'I';
        }
        return $comensal;
    }

    public function getEmpleados($cedula)
    {
        $comensal = DB::connection('mysql_third')
            ->table('rrhh_vista_personal')
            ->where('per_cedula', $cedula)
            ->first();

        // obtenemos el estatus del empleado 1:ACTIVO
        $comensal->estatus = DB::connection('mysql_third')
            ->table('rrhh_personal')
            ->where('per_cedula', $cedula)
            ->first()->per_status;

        $comensal->sexo = DB::connection('mysql_third')
            ->table('rrhh_personal')
            ->join('tools_sexo', 'tools_sexo.sex_codigo', '=', 'per_sexo')
            ->where('per_cedula', $cedula)
            ->first()->sex_descripcion;


        if ($comensal) {
            $comensal->tipo_comensal = "EMPLEADO";
        }

        $comensal = $this->adaptadorDeComensal($comensal);

        return $comensal;
    }

    public function adaptadorDeComensal($queryComensal)
    {

        $comensalObj = new \stdClass();
        $comensalObj->nombres = $queryComensal->per_nombres;
        $comensalObj->apellidos = $queryComensal->per_apellidos;
        $comensalObj->nacionalidad = "V";
        $comensalObj->cedula = $queryComensal->per_cedula;
        $comensalObj->sexo = strtoupper($queryComensal->sexo) == "MASCULINO" ? 'M' : 'F';
        $comensalObj->estatus = $queryComensal->estatus;
        $comensalObj->tipo_comensal = $queryComensal->tipo_comensal;
        $comensalObj->sede = $queryComensal->vicn_descripcion;
        $comensalObj->direccion = $queryComensal->Nombre_Completo;
        // para mantener compatibilidad con la lógica que usa count($comensal->carreras)
        $comensalObj->carreras = [false];
    }
}
