<?php

namespace App\Models;

use App\Models\{
    Cuota,
    Dificultade,
    Estudiante,
    Grupo,
    GrupoEstudiante,
    User,
    RolPermiso,
    Permiso,
    Role,
    Inscripcione,
    Representante,
    RepresentanteEstudiante
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Helpers extends Model
{
    use HasFactory;

    public static $estudiantes;
    public static $fechaCuota;

    public static function getServicio( $ahora ){

        /** Obtenemos los servicios */
        $servicios = Servicio::all();

        foreach ( $servicios as $key => $servicio ) {
            $hora_inicio = explode(':', $servicio->hora_inicio );
            $hora_cierre = explode(':', $servicio->hora_cierre );
            /** Configuracion de rango de tiempo de comida ALMUERZO */
            $almuerzoInferior = Carbon::now()
              ->startOfDay()
              ->addHours($hora_inicio[0])
              ->addMinute($hora_inicio[1]);
    
            $almuerzoSuperior = $almuerzoInferior->copy()
                ->addHours($hora_cierre[0] - $hora_inicio[0])
                ->addMinute($hora_cierre[1]);
            
            if($ahora->lessThan($almuerzoSuperior) == true && $ahora->greaterThan($almuerzoInferior) == true){
                return $servicio;
            }
            
        }

        return false;
      
    }

    public static function getTotalEntradas($date, $nameServicio){
        return Entrada::where([
            'fecha' =>  $date,
            'comida' => $nameServicio
        ])->count();
    }

    /** Marcar entrada del comensal */
    public static function setEntradaComedor( $comensal, $servicio ){
        try {
            $date = Carbon::now();
            Entrada::create([
                'nombres' => $comensal->nombres,
                'apellidos' => $comensal->apellidos,
                'nacionalidad' => $comensal->nacionalidad,  
                'cedula' => $comensal->cedula, 
                'sexo' => $comensal->sexo, 
                'comida' => $servicio->nombre, 
                'tipo_comensal' => $comensal->tipo_comensal ?? 'ESTUDIANTE',  

                'codigo_carrera' => $comensal->carreras[0]->codigo_carrera ?? '', 
                'carrera' => $comensal->carreras[0]->nombre_carrera ?? '', 

                'codigo_sede' => $comensal->carreras[0]->codigo_sede ?? '', 
                'sede' => $comensal->carreras[0]->nombre_sede ?? $comensal->sede, 
                'tipo_sede' => $comensal->carreras[0]->tipo_sede ?? '',

                'estado' => $comensal->carreras[0]->estado_sede ?? '',
                'municipio' => $comensal->carreras[0]->municipio_sede ?? '',
                'direccion' => $comensal->carreras[0]->sector_sede ?? $comensal->direccion,
                
           
                'fecha' => $date->format('d-m-Y'),
                'hora' => $date->format('h:ia'),    
            ]);
            
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, ", ¡Error interno al intentar registrar la entrada del comensal!");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    /** Respuesta JSON */
    public static function getRespuestaJson($mensaje, $data = [], $estatus = Response::HTTP_OK)
    {
        return response()->json([
            "mensaje" => $mensaje,
            "data" => $data,
            "estatus" => $estatus
        ], $estatus);
    }

    public static function setFechasHorasNormalizadas($datos)
    {
        $fechaInscripcion = Carbon::parse($datos->fecha);
        $dtInit = Carbon::parse($datos->grupo_fecha_inicio);
        $dtEnd = Carbon::parse($datos->grupo_fecha_fin);
        $htInit = Carbon::parse($datos->grupo_hora_inicio);
        $htEnd = Carbon::parse($datos->grupo_hora_fin);

        // Normalizando fechas y horas
        $datos->fecha_init = $dtInit->format('d/m/Y');
        $datos->fecha_end = $dtEnd->format('d/m/Y');
        $datos->hora_init = $htInit->format('h:ia');
        $datos->hora_end = $htEnd->format('h:ia');
        $datos->fecha = $fechaInscripcion->format('d/m/Y');

        return $datos;
    }

    public static function normalizarFecha($fecha, $formato = 'd/m/Y')
    {
        return  date_format(date_create($fecha), $formato);
    }

    public static function normalizarHora($hora, $formato = 'h:ia')
    {
        $newHora = Carbon::parse($hora);
        return $newHora->format($formato);
    }

    public static function getUsuarios()
    {
        $usuarios = User::where('rol', '!=', 1)->get();
        foreach ($usuarios as $key => $usuario) {
            $usuarios[$key] = self::getUsuario($usuario->id);
        }
        return $usuarios;
    }

    public static function getUsuario($id)
    {
        $usuario = User::where("id", $id)->get()[0];
        if ($usuario) {
        //     $usuario->permisos = self::getPermisosUsuario(RolPermiso::where("id_rol", $usuario->rol)->get());
            $usuario->rol = Role::where("id", $usuario->rol)->first();
        }
        return $usuario;
    }

    public static function getPermisosUsuario($permisos)
    {
        $permisosObject = [];
        foreach ($permisos as $permiso) {
            $permisosObject[$permiso->id_permiso] = Permiso::where('id', $permiso->id_permiso)->get()[0];
        }
        return $permisosObject;
    }

    public static function getMensajeError($e, $mensaje)
    {
            $errorInfo = $mensaje . " ("  
                . $e->getMessage() . ")." . "<br>"
                . "Código de error: " . $e->getCode() . "<br>"
                // . "Linea de error: " . $e->getLine() . "<br>"
                // . "El archivo: " . $e->getFile() . "<br>"
                ?? 'No hay mensaje de error';
       
        return $errorInfo;
    }

    /**
     * Esta funcion retorna los checkbox activos de los elementos deseados
     * @param datos array
     * @param inputChecks array
     */

    public static function getCheckboxActivo($datos, $inputChecks)
    {
        foreach ($datos as $key => $dato) {
            $dato->activo = 0;
            foreach ($inputChecks as $check) {
                if ($dato->id == $check) $dato->activo = 1;
            }
        }
        return $datos;
    }

     /**
     * Esta funcion recibe la informacion del formulario y detecta cuales son los input que
     * contienen el prefijo @var dif_ y las convierte en un array.
     *
     * @param Request
     */
    public static function getInputsEnArray($request, $prefijoInputs)
    {
        $arrayInput = [];
        $arrayInputAssoc = [];
        foreach ($prefijoInputs as $prefijo) {
            foreach ($request->all() as $key => $value) {
                $text = substr($key, 0, 6);
                if ($text == $prefijo) : $arrayInput[$key] = $value;
                    continue;
                endif;
            }
        }

        foreach ($arrayInput as $key => $value) {
            $id = substr($key, 6, 7);
            $arrayInputAssoc[$id][substr($key, 0, 5)] =  $value;
        }


        return $arrayInputAssoc;
    }


    /**
     * Esta funcion se encarga de guardar la imagen en el store en la direccion public/fotos
     * recibe los siguientes parametros
     * @param request  Estes es el elemento global de las peticiones y se accede a su metodo file y atributo file
     * @return url Retorna la direccion donde se almaceno la imagen
     */
    public static function setFile($request)
    {
        // Movemos la imagen a storage/app/public/fotos
        $imagen = $request->file('file')->store('public/fotos');

        // configuramos la url de /public a /storage
        $url = Storage::url($imagen);

        // Retorna la URL de la imagen
        return $url;
    }

    /**
     * Eliminamos la imagen o archivo anterior
     * @param url se solicita la url del archivo para removerlo de su ubicacion
     */
    public static function removeFile($url)
    {
        $paths = str_replace('storage', 'public', $url);
        if (Storage::delete($paths)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Seteamos la data relacional a los grupos y retornamos los datos
     *
     * @param array
     * Este recibe el arreglo donde se desea añadir la informacion de las relaciones.
     *
     * @param arrayKey
     * Este parametro recibe un array asociativo que el key hace referencia a la tabla de la base de datos
     * y el valor al key de relacion a la otra tabla de la DB.
     *
     * ejemplo: ["profesores" => "cedula_profesor"]
     * Aqui buscamos los datos de la tabla grupos
     * desde el cedula_profesor
     *
     */

    public static function addDatosDeRelacion($array, $arrayKey, $sqlExtra = "")
    {
        if (count($array)) {
            foreach ($array as $key => $value) {
                foreach ($arrayKey as $keyTable => $valueKey) {
                    $llave = explode("_", $valueKey);
                    // return DB::select("select * from {$keyTable} where {$llave[0]} = :{$valueKey} {$sqlExtra}", [$value[$valueKey]]);
                    $array[$key][$llave[1]] = count(DB::select("select * from {$keyTable} where {$llave[0]} = :{$valueKey} {$sqlExtra}", [$value[$valueKey]])) > 1
                        ? DB::select("select * from {$keyTable} where {$llave[0]} = :{$valueKey} {$sqlExtra}", [$value[$valueKey]])
                        : DB::select("select * from {$keyTable} where {$llave[0]} = :{$valueKey} {$sqlExtra}", [$value[$valueKey]])[0] ?? [];
                }
            }
        }

        return $array;
    }

    /**
     * @param Object ### Recibe un objeto ###
     *  Esta funcion se encarga de convertir un objecto en una Arreglo Asociativo y asigna
     *  una llave o posicion [0]->data
     *
     */
    public static function setConvertirObjetoParaArreglo($object)
    {
        return [get_object_vars($object)];
    }
    //


    /**
     * Validar si el dato existe
     */

    public static function datoExiste($data, $array = ["tabla" => ["campo", "sqlExtra", "key"]])
    {
        foreach ($array as $key => $value) {
            return $result = count(DB::select("select * from {$key} where {$value[0]} = :codigo {$value[1]}", [$data[$value[2]]]))
                ? DB::select("select * from {$key} where {$value[0]} = :codigo {$value[1]}", [$data[$value[2]]])[0]
                : false;
        }
    }

    public static function auto_decimal_format($n, $def = 2)
    {
        $a = explode(".", $n);
        if (count($a) > 1) {
            $b = str_split($a[1]);
            $pos = 1;
            foreach ($b as $value) {
                if ($value != 0 && $pos >= $def) {
                    $c = number_format($n, $pos);
                    $c_len = strlen(substr(strrchr($c, "."), 1));
                    if ($c_len > $def) {
                        return rtrim($c, 0);
                    }
                    return $c; // or break
                }
                $pos++;
            }
        }
        return number_format($n, $def);
    }
} // end
