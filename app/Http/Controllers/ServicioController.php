<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use App\Models\DataDev;
use App\Models\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class ServicioController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }
    

    public function index(Request $request)
    {
        try {
            $servicios = Servicio::paginate(6);
    
            $respuesta = $this->data->respuesta;
            return view('admin.servicios.index', compact('respuesta', 'request', 'servicios'));
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al consultar los servicios.");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            return back()->with(compact('mensaje', 'estatus'));
        }
    }


    public function store(StoreServicioRequest $request){
        $mensaje = "Servicio registrado correctamente";
        $estatus = Response::HTTP_CREATED;
    
        try {
            Servicio::create($request->all());
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al registrar un servicios.");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        } finally{
            return back()->with(compact('mensaje', 'estatus'));
        }
    }

    public function update(UpdateServicioRequest $request, Servicio $servicio ){
        $mensaje = "Servicio actualizado correctamente";
        $estatus = Response::HTTP_OK;
        try {
            $servicio->update($request->all());
        } catch (\Throwable $th) {
             $mensaje = Helpers::getMensajeError($th, "Error al editar datos del servicios.");
            $estatus = Response::HTTP_INTERNAL_SERVER_ERROR;
          
        } finally {
            return back()->with(compact('mensaje', 'estatus'));
        }

    }

    public function destroy(){}
}
