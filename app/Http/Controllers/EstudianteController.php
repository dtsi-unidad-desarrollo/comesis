<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;


use App\Models\{
    Estudiante,
    DificultadEstudiante,
    Helpers,
    DataDev
};
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class EstudianteController extends Controller
{

    public $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = new DataDev;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comensales = [];

        if ($request->filtro) {
            $comensales = Estudiante::where('cedula', 'like', "%{$request->filtro}%")
                ->orWhere('nombres', 'like', "%{$request->filtro}%")
                ->orWhere('apellidos', 'like', "%{$request->filtro}%")
                ->orWhere('carrera', 'like', "%{$request->filtro}%")
                ->orWhere('tipo', '=', $request->filtro)
                ->orderBy('id', 'desc')->paginate(12);
        } else {
            $comensales = Estudiante::paginate(12);
        }

        $respuesta =  $this->data->respuesta;

        return view('admin.estudiantes.lista', compact('comensales', 'request', 'respuesta'));
    }

    public function sincronizarData()
    {
        try {


            $datos = DB::connection('mysql_second')->table('estudiantes')->select('nombres', 'apellidos', 'nacionalidad', 'Cedula', 'Sexo')->get();

            foreach ($datos as $key => $comensal) {
                $codigoCarrera = "";
                $estatusEstudiante = 0;
                $carreras = DB::connection('mysql_second')->table('carreras_est')->where('ConexEst',  $comensal->Cedula)->select('Status', 'CodCar')->get();

                if(count($carreras)){
                    $estatusCarrera = [];
                    foreach ($carreras as $key => $carrera) {
                        if ($carrera->Status == "A") {
                            array_push($estatusCarrera, $carrera);
                        }
                    }
                    if (count($estatusCarrera)) {
                        $codigoCarrera = $estatusCarrera[0]->CodCar;
                        $estatusEstudiante =  1;
                    } else {
                        $codigoCarrera = $carreras[0]->CodCar;
                    }
                }


                $siExiste = Estudiante::where('cedula', $comensal->Cedula)->first();
                if ($siExiste) {
                    // actualizamos los datos
                    $siExiste->update([
                        "nombres" => $comensal->nombres,
                        "apellidos" => $comensal->apellidos,
                        'nacionalidad' => $comensal->nacionalidad,
                        'cedula' => $comensal->Cedula,
                        'carrera'  =>  $codigoCarrera,
                        'tipo' => 'ESTUDIANTE',
                        'estatus' => $estatusEstudiante
                    ]);
                } else {
                    // registramos en el sistema
                    Estudiante::create([
                        "nombres" => $comensal->nombres,
                        "apellidos" => $comensal->apellidos,
                        'nacionalidad' => $comensal->nacionalidad,
                        'cedula' => $comensal->Cedula,
                        'carrera'  => $codigoCarrera,
                        'tipo' => 'ESTUDIANTE',
                        'estatus' => $estatusEstudiante
                    ]);
                }
            }

            $mensaje = "Datos sincronizados correctamente!";
            $estatus = Response::HTTP_OK;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar sincronizar los comensales!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEstudianteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEstudianteRequest $request)
    {
        try {
            // Validando cedula 
            $estatusCreate = 0;

            // Validamos si se envio una foto
            if (isset($request->file)) {
                $request['foto'] = Helpers::setFile($request);
            }

            // registramos el estudiante
            $estatusCreate = Estudiante::create($request->all());

            $mensaje =  $estatusCreate   ? "Estudiante registrado correctamente"
                : "No se pudo registrar verifique los datos.";
            $estatus = $estatusCreate ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;

            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar registrar estudiante!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEstudianteRequest  $request
     * @param  \App\Models\Estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEstudianteRequest $request, $id)
    {

        try {
            if (!$request->estatus) {
                $request['estatus'] = 0;
            }

            $estudiante = Estudiante::where('id', $id)->first();

            // Validamos si se envio una foto
            if (isset($request->file)) {
                // Eliminamos la imagen anterior
                $fotoActual = explode('/', $estudiante->foto);
                if ($fotoActual[count($fotoActual) - 1] != 'avatar.png') {
                    Helpers::removeFile($estudiante->foto);
                }

                // Insertamos la nueva imagen o archivo
                $request['foto'] = Helpers::setFile($request);
            } else {
                $request['foto'] = $estudiante->foto;
            }

            // Actualizamos los datos de lestudiante
            $estudiante->update($request->all());

            $mensaje = "Los Datos del comensal se guardaron correctamente";
            $estatus = Response::HTTP_OK;

            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            //throw $th;
            $mensaje = Helpers::getMensajeError($th, ", Error interno al intentar editar los datos del comensal");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $estudiante = Estudiante::where('id', $id)->first();
            $estudiante->update(["estatus" => 0]);

            $mensaje = "El comensal {$estudiante->nombre}, fue desactivado correctamente.";
            $estatus = 200;
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error interno al intentar desactivar el comensal");
            $estatus = 301;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }
}
