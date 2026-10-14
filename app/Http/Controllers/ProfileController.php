<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $currentSessionId = session()->getId();

        $sessions = DB::table('sessions')
            ->where('user_id', $usuario->id)
            ->orderByDesc('last_activity')
            ->get();

        return view('admin.perfil.index', compact('usuario', 'sessions', 'currentSessionId'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
        ];

        $passwordChanged = false;
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $passwordChanged = true;
        }

        $user->update($data);

        // Si se cambió la contraseña, revocar automáticamente todas las demás sesiones por seguridad
        if ($passwordChanged) {
            $currentSessionId = session()->getId();
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $currentSessionId)
                ->delete();
        }

        if ($user->wasChanged()) {
            AuditLogger::log('update_profile', 'user', $user->id, [
                'message' => 'El usuario actualizó su perfil' . ($passwordChanged ? ' y cambió su contraseña' : ''),
                'email' => $request->email,
            ]);
        }

        return redirect()->route('admin.perfil.index')->with([
            'mensaje' => 'Tu perfil se actualizó correctamente.' . ($passwordChanged ? ' Se han cerrado las sesiones en otros dispositivos por seguridad.' : ''),
            'estatus' => 200,
        ]);
    }

    /**
     * Cierra todas las demás sesiones activas del usuario actual, excepto la sesión actual.
     */
    public function revokeOtherSessions(Request $request)
    {
        $user = Auth::user();
        $currentSessionId = session()->getId();

        $deleted = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        AuditLogger::log('revoke_other_sessions', 'user', $user->id, [
            'message' => 'El usuario cerró sesión en todos los demás dispositivos',
            'revoked_count' => $deleted,
        ]);

        return back()->with([
            'mensaje' => "Se han cerrado {$deleted} sesiones en otros dispositivos.",
            'estatus' => 200,
        ]);
    }
}
