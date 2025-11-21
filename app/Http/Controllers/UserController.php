<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\{
    User,
    DataDev,
    Helpers,
    Permiso,
    Role
};
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }
    public function index()
    {
        try {
            $usuarios = Helpers::getUsuarios();
            $respuesta = $this->data->respuesta;
            return view('admin.usuarios.lista', compact('usuarios', 'respuesta'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al Consultar datos de usuarios en el metodo index,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    public function create()
    {
        try {
            $roles = Role::where('estatus', 1)->where('nombre', '!=', 'ROOT')->get();
            return view('admin.usuarios.crear', compact('roles'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al Consultar datos de usuarios en el metodo create,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }


    public function store(StoreUserRequest $request)
    {
        try {


            // Seteamos la foto
            if (isset($request->file)) {
                $request['foto'] = Helpers::setFile($request);
            }
            // Encriptamos la contraseña
            $request['password'] = Hash::make($request['password']);
            // Creamos el usuario
            $estatusCreate = User::create($request->all());

            $mensaje = $estatusCreate ? "El Usuario se Registró correctamente."
                : "El usuario no se registro!";
            $estatus = $estatusCreate ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

            return redirect()->route('admin.users.index')->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al Registrar los datos de usuarios en el metodo store,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    public function edit(User $user)
    {

        try {
            $usuario = $user;
            $roles = Role::where('estatus', 1)->where('nombre', '!=', 'ROOT')->get();
            return view('admin.usuarios.editar', compact('usuario', 'roles'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error de consula,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            if ($user->email != $request->email) {
                $userExits =  User::where('email', $request->email)->first();
                if ($userExits) {
                    $mensaje = "El email ingresado ya esta registrado, por favor use otro!";
                    $estatus = Response::HTTP_BAD_REQUEST;
                    return back()->with(compact('mensaje', 'estatus'));
                }
            }

            // Validamos si se envio una foto
            if (isset($request->file)) {
                // Eliminamos la imagen anterior
                $fotoActual = explode('/', $user->foto);
                if ($fotoActual[count($fotoActual) - 1] != 'default.jpg') {
                    Helpers::removeFile($user->foto);
                }
                // Insertamos la nueva imagen o archivo
                $request['foto'] = Helpers::setFile($request);
            } else {
                $request['foto'] = $user->foto;
            }
            // Encriptamos la contraseña
            if (isset($request['password'])) {
                $request['password'] = Hash::make($request['password']);
            } else {
                $request['password'] = $user['password'];
            }

            $updated = $user->update($request->all());

            $mensaje = $updated ? "El Usuario se Actualizó correctamente." : "No se guardaron los datos!";
            $estatus = $updated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
            return redirect()->route('admin.users.index')->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error de al intentar Actualizar un usuario,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            $mensaje = "El Usuario se Eliminó correctamente.";
            $estatus = Response::HTTP_OK;
            return redirect()->route('admin.users.index')->with( compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error de al intentar Eliminar un usuario,");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }
}
