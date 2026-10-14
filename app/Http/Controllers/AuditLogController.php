<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Atm;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AuditLogExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()->orderByDesc('created_at');

        if ($request->filled('usuario')) {
            $query->where('user_name', 'like', '%' . $request->usuario . '%');
        }

        if ($request->filled('accion')) {
            $query->where('action', 'like', '%' . $request->accion . '%');
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $auditorias = $query->paginate(20)->appends($request->query());

        // Sesiones activas en tiempo real
        $currentSessionId = session()->getId();
        $activeSessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->leftJoin('atms', 'users.id', '=', 'atms.user_id')
            ->select(
                'sessions.id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'users.nombre as user_name',
                'users.email as user_email',
                'users.rol as user_rol',
                'atms.nombre as atm_nombre'
            )
            ->orderByDesc('sessions.last_activity')
            ->get();

        return view('admin.auditoria.index', compact('auditorias', 'activeSessions', 'currentSessionId'));
    }

    /**
     * Revoca forzosamente una sesión activa desde el monitor de auditoría.
     */
    public function revokeSession(Request $request, $sessionId)
    {
        $session = DB::table('sessions')->where('id', $sessionId)->first();

        if ($session) {
            // Si la sesión tenía una taquilla asignada al usuario, liberarla
            if ($session->user_id) {
                Atm::where('user_id', $session->user_id)->update([
                    'en_uso' => false,
                    'user_id' => null,
                    'last_active_at' => null,
                ]);
            }

            DB::table('sessions')->where('id', $sessionId)->delete();

            AuditLogger::log('session_revoked', 'session', null, [
                'revoked_session_id' => $sessionId,
                'target_user_id' => $session->user_id,
                'ip_address' => $session->ip_address,
                'message' => 'Sesión revocada forzosamente por administrador',
            ]);

            return back()->with([
                'mensaje' => 'La sesión ha sido desconectada exitosamente.',
                'estatus' => 200,
            ]);
        }

        return back()->with([
            'mensaje' => 'La sesión ya no existe o ya había expirado.',
            'estatus' => 404,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $query = AuditLog::query();

        if ($request->filled('usuario')) {
            $query->where('user_name', 'like', '%' . $request->usuario . '%');
        }

        if ($request->filled('accion')) {
            $query->where('action', 'like', '%' . $request->accion . '%');
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        return Excel::download(new AuditLogExport($query->get()), 'auditoria.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = AuditLog::query()->orderByDesc('created_at');

        if ($request->filled('usuario')) {
            $query->where('user_name', 'like', '%' . $request->usuario . '%');
        }

        if ($request->filled('accion')) {
            $query->where('action', 'like', '%' . $request->accion . '%');
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $auditorias = $query->get();
        $pdf = Pdf::loadView('admin.auditoria.pdf', compact('auditorias'));

        return $pdf->download('auditoria.pdf');
    }
}
