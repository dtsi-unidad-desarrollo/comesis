<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        return view('admin.perfil.index', compact('usuario'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($user->wasChanged()) {
            AuditLogger::log('update_profile', 'user', $user->id, [
                'message' => 'El usuario actualizó su perfil',
                'email' => $request->email,
            ]);
        }

        return redirect()->route('admin.perfil.index')->with([
            'mensaje' => 'Tu perfil se actualizó correctamente.',
            'estatus' => 200,
        ]);
    }
}
