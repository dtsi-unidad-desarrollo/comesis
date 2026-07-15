<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
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

        return view('admin.auditoria.index', compact('auditorias'));
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
