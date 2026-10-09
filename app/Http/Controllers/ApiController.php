<?php

namespace App\Http\Controllers;

use App\Models\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /** 
     * Punto de acceso para la data de estudiantes 
     */
    public function apiEstudiantes()
    {
        // Estado conexión DUX (mysql_second)
        $duxStatus = [
            'connected' => false,
            'host' => config('database.connections.mysql_second.host'),
            'database' => config('database.connections.mysql_second.database'),
            'total_remoto' => 0,
            'activos_remoto' => 0,
            'error' => null,
        ];

        try {
            DB::connection('mysql_second')->getPdo();
            $duxStatus['connected'] = true;
            $duxStatus['total_remoto'] = DB::connection('mysql_second')->table('estudiantes')->count();
            $duxStatus['activos_remoto'] = DB::connection('mysql_second')
                ->table('estudiantes as e')
                ->join('carreras_est as c', 'c.ConexEst', '=', 'e.Cedula')
                ->where('c.Status', 'A')
                ->distinct()
                ->count('e.Cedula');


            // obtenemos todos los estudiantes
            $obtenerEstudiantes = DB::connection('mysql_second')
                ->table('estudiantes as e')
                ->join('carreras_est as c', 'c.ConexEst', '=', 'e.Cedula')
                ->join('carreras as ca', 'c.CodCar', '=', 'ca.CodCar')
                ->where('c.Status', 'A')
                ->distinct()
                ->get(['e.nombres', 'e.apellidos', 'e.nacionalidad', 'e.Cedula as cedula', 'e.Sexo as sexo', 'c.Status as estatus', 'ca.NombCar']);

            return Helpers::getRespuestaJson(
                "OK",
                [
                    "duxStatus" => $duxStatus,
                    "data" => $obtenerEstudiantes,
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            $duxStatus['error'] = $e->getMessage();
            return Helpers::getRespuestaJson(
                "Error Server",
                [
                    "duxStatus" => $duxStatus,
                    "data" => null
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /** 
     * Punto de acceso para la data de empleados 
     */
    public function apiEmpleados()
    {
        $terepaimaStatus = [
            'connected' => false,
            'host' => config('database.connections.mysql_third.host'),
            'database' => config('database.connections.mysql_third.database'),
            'total_remoto' => 0,
            'activos_remoto' => 0,
            'error' => null,
        ];

        try {
            DB::connection('mysql_third')->getPdo();
            $terepaimaStatus['connected'] = true;
            $terepaimaStatus['total_remoto'] = DB::connection('mysql_third')->table('personal')->count();
            $terepaimaStatus['activos_remoto'] = DB::connection('mysql_third')
                ->table('estudiantes as e')
                ->join('carreras_est as c', 'c.ConexEst', '=', 'e.Cedula')
                ->where('c.Status', 'A')
                ->distinct()
                ->count('e.Cedula');


            $schema = DB::connection('mysql_third')->getSchemaBuilder();
            $datosEmpleados = collect();

            if ($schema->hasTable('personal')) {
                $excludeNames = ['DOCENTE FALLECIDO', 'OBRERO FALLECIDO', 'ADMINISTRATIVO FALLECIDO'];
                $datosEmpleados = DB::connection('mysql_third')
                    ->table('personal')
                    ->select('per_nombres', 'per_apellidos', 'per_nacionalidad', 'per_cedula', 'per_sexo', 'per_status', 'nom_nombre as sub_tipo', 'nom_status', 'Nombre_Completo as ubicacion_laboral')
                    ->whereNotIn('nom_nombre', $excludeNames)
                    ->get();
            } elseif ($schema->hasTable('rrhh_personal')) {
                $datosEmpleados = DB::connection('mysql_third')
                    ->table('rrhh_personal')
                    ->join('rrhh_vista_personal', 'rrhh_personal.per_cedula', '=', 'rrhh_vista_personal.per_cedula')
                    ->select(
                        'rrhh_vista_personal.per_nombres',
                        'rrhh_vista_personal.per_apellidos',
                        'rrhh_personal.per_cedula',
                        'rrhh_personal.per_sexo',
                        'rrhh_personal.per_status',
                        'rrhh_vista_personal.vicn_descripcion as sub_tipo',
                        'rrhh_vista_personal.Nombre_Completo as ubicacion_laboral'
                    )
                    ->get();
            }

            return Helpers::getRespuestaJson(
                "OK",
                [
                    "duxStatus" => null,
                    "data" => null,
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
             $terepaimaStatus['error'] = $e->getMessage();
            return Helpers::getRespuestaJson(
                "OK",
                [
                    "terepaimaStatus" =>  $terepaimaStatus,
                    "data" => null,
                ],
                Response::HTTP_OK
            );
        }
    }
}
