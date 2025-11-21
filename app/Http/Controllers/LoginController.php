<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('login');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Autenticamos al usuario
        $credenciales = $request->only('email', 'password');

        // $recuerdame = isset($request->recuerdame) ? true : false;
        $recuerdame = $request->filled('rememberMe');

        if (Auth::attempt($credenciales, $recuerdame)) {
            $request->session()->regenerate();
            if(Auth::user()->rol == 1 || Auth::user()->rol == 2 ){
                return redirect()->route('admin.panel.index');
            }else{
                return redirect()->route('admin.recepcion.index');
            }
        }

        return back()->withErrors([
            'mensaje' => 'Credenciales de acceso no validas.',
        ]);
    }

 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request, Redirector $redirect)
    {
      
        // Eliminamos la session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return $redirect->to('login');
    }
}
