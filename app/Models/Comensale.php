<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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

    public  function executeSincronizarEstudiantes()
    {
        try {

            $datosEstudiantes = DB::connection('mysql_second')
                ->table('estudiantes')
                ->select('nombres', 'apellidos', 'nacionalidad', 'Cedula as cedula', 'Sexo as sexo')
                ->get();

            foreach ($datosEstudiantes as $comensal) {
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
                            'nacionalidad' => $comensal->nacionalidad,
                            'sexo' => strtoupper(substr(trim($comensal->sexo), 0, 1)) === 'F' ? 'F' : 'M',
                            'tipo_comensal' => 'ESTUDIANTE',
                            'foto' =>  "https://arse.unellez.edu.ve/fotos/" . $comensal->cedula . ".jpg",
                            'estatus' => $estatusEstudiante,
                        ]
                    );
                }else{
                    // Si el estudiante no está activo, eliminamos el registro de la tabla comensales
                    Comensale::where('cedula', $comensal->cedula)->delete();
                }
            }

            // informar en el log o en la respuesta que se completo la sincronización de estudiantes
            $mensaje = "Sincronización de estudiantes completada correctamente.";
            $estatus = Response::HTTP_OK;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {

            $mensaje = Helpers::getMensajeError($th, ', ¡Error interno al intentar sincronizar los estudiantes!');
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;

            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    public function executeSincronizarEmpleados()
    {
        try {

            $datosEmpleados = DB::connection('mysql_third')
                ->table('personal')
                ->select('per_nombres', 'per_apellidos', 'per_nacionalidad', 'per_cedula', 'per_sexo', 'per_status', 'nom_nombre', 'nom_status', 'Nombre_Completo as ubicacion_laboral')
                ->where('nom_status', 1)
                ->whereNotIn('nom_nombre', ['DOCENTE FALLECIDO', 'OBRERO FALLECIDO', 'ADMINISTRATIVO FALLECIDO'])
                ->get();

            foreach ($datosEmpleados as $empleado) {
                if ($empleado->nom_status == 1) {
                    // Actualizar o crear el registro del empleado en la tabla comensales
                    Comensale::updateOrCreate(
                        ['cedula' => $empleado->per_cedula],
                        [
                            'nombres' => $empleado->per_nombres,
                            'apellidos' => $empleado->per_apellidos,
                            'nacionalidad' => 'V',
                            'sexo' => $empleado->per_sexo === 1 ? 'M' : 'F',
                            'tipo_comensal' => 'EMPLEADO',
                            'sub_tipo' => $empleado->nom_nombre ?? 'N/A',
                            'observacion' => $empleado->ubicacion_laboral ?? '',
                            'foto' => "/assets/img/avatar.png",
                            'estatus' => $empleado->nom_status == 1 ? 1 : 0,
                        ]
                    );
                }else{
                    // Si el empleado no está activo, eliminamos el registro de la tabla comensales
                    Comensale::where('cedula', $empleado->per_cedula)->delete();
                }
            }

            $mensaje = "Sincronización de empleados completada correctamente.";
            $estatus = Response::HTTP_OK;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {

            $mensaje = Helpers::getMensajeError($th, ', ¡Error interno al intentar sincronizar los empleados!');
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;

            return back()->with(compact('mensaje', 'estatus'));
        }
    }
}
