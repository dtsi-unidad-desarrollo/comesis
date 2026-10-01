<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Comensale extends Model
{
    use HasFactory;

    protected $attributes = [
        'sub_tipo' => '',
    ];

    protected $fillable = [
        'nombres',
        'apellidos',
        'nacionalidad',
        'cedula',
        'sexo',
        'tipo_comensal',
        'sub_tipo',
        'observacion',
        'foto',
        'datos_extras',
        'estatus',
    ];

    /**
     * Ejecuta la sincronización de estudiantes desde el sistema DUX (mysql_second).
     *
     * @return array
     */
    public static function executeSincronizarEstudiantes()
    {
        try {
            $datosEstudiantes = DB::connection('mysql_second')
                ->table('estudiantes')
                ->select('nombres', 'apellidos', 'nacionalidad', 'Cedula as cedula', 'Sexo as sexo')
                ->get();

            $totalProcesados = 0;
            $sincronizados = 0;
            $desactivados = 0;

            foreach ($datosEstudiantes as $comensal) {
                $totalProcesados++;
                $estatusEstudiante = 0;

                $carreras = DB::connection('mysql_second')
                    ->table('carreras_est')
                    ->where('ConexEst', $comensal->cedula)
                    ->select('Status', 'CodCar')
                    ->get();

                foreach ($carreras as $carrera) {
                    if ($carrera->Status === 'A') {
                        $estatusEstudiante = 1;
                        break;
                    }
                }

                if ($estatusEstudiante) {
                    Comensale::updateOrCreate(
                        ['cedula' => $comensal->cedula],
                        [
                            'nombres' => $comensal->nombres,
                            'apellidos' => $comensal->apellidos,
                            'nacionalidad' => $comensal->nacionalidad ?? 'V',
                            'sexo' => strtoupper(substr(trim($comensal->sexo ?? 'M'), 0, 1)) === 'F' ? 'F' : 'M',
                            'tipo_comensal' => 'ESTUDIANTE',
                            'foto' => "https://arse.unellez.edu.ve/fotos/" . $comensal->cedula . ".jpg",
                            'estatus' => 1,
                        ]
                    );
                    $sincronizados++;
                } else {
                    // Si el estudiante no está activo en carreras, eliminamos o desactivamos
                    $eliminado = Comensale::where('cedula', $comensal->cedula)->delete();
                    if ($eliminado) {
                        $desactivados++;
                    }
                }
            }

            $mensaje = "Sincronización de estudiantes con DUX completada: {$sincronizados} activos actualizados.";
            Log::info($mensaje, [
                'total_remotos' => $totalProcesados,
                'sincronizados' => $sincronizados,
                'desactivados' => $desactivados,
            ]);

            return [
                'success' => true,
                'total' => $totalProcesados,
                'sincronizados' => $sincronizados,
                'desactivados' => $desactivados,
                'mensaje' => $mensaje,
            ];
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, 'Error interno al intentar sincronizar los estudiantes con DUX');
            Log::error($mensaje, ['exception' => $th]);

            return [
                'success' => false,
                'total' => 0,
                'sincronizados' => 0,
                'desactivados' => 0,
                'mensaje' => $mensaje,
                'error' => $th->getMessage(),
            ];
        }
    }

    /**
     * Ejecuta la sincronización de personal/empleados desde el sistema TEREPAIMA (mysql_third).
     *
     * @return array
     */
    public static function executeSincronizarEmpleados()
    {
        try {
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

            $totalProcesados = 0;
            $sincronizados = 0;
            $desactivados = 0;

            foreach ($datosEmpleados as $empleado) {
                $totalProcesados++;
                $status = isset($empleado->nom_status) ? (int)$empleado->nom_status : (int)$empleado->per_status;

                if ($status === 1) {
                    $sexo = 'M';
                    if (isset($empleado->per_sexo)) {
                        $s = (string)$empleado->per_sexo;
                        $sexo = ($s === '2' || strtoupper(substr(trim($s), 0, 1)) === 'F') ? 'F' : 'M';
                    }

                    Comensale::updateOrCreate(
                        ['cedula' => $empleado->per_cedula],
                        [
                            'nombres' => $empleado->per_nombres ?? '',
                            'apellidos' => $empleado->per_apellidos ?? '',
                            'nacionalidad' => $empleado->per_nacionalidad ?? 'V',
                            'sexo' => $sexo,
                            'tipo_comensal' => 'EMPLEADO',
                            'sub_tipo' => $empleado->sub_tipo ?? 'PERSONAL',
                            'observacion' => $empleado->ubicacion_laboral ?? '',
                            'foto' => '/assets/img/avatar.png',
                            'estatus' => 1,
                        ]
                    );
                    $sincronizados++;
                } else {
                    $eliminado = Comensale::where('cedula', $empleado->per_cedula)->delete();
                    if ($eliminado) {
                        $desactivados++;
                    }
                }
            }

            $mensaje = "Sincronización de empleados con TEREPAIMA completada: {$sincronizados} activos actualizados.";
            Log::info($mensaje, [
                'total_remotos' => $totalProcesados,
                'sincronizados' => $sincronizados,
                'desactivados' => $desactivados,
            ]);

            return [
                'success' => true,
                'total' => $totalProcesados,
                'sincronizados' => $sincronizados,
                'desactivados' => $desactivados,
                'mensaje' => $mensaje,
            ];
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, 'Error interno al intentar sincronizar los empleados con TEREPAIMA');
            Log::error($mensaje, ['exception' => $th]);

            return [
                'success' => false,
                'total' => 0,
                'sincronizados' => 0,
                'desactivados' => 0,
                'mensaje' => $mensaje,
                'error' => $th->getMessage(),
            ];
        }
    }
}
