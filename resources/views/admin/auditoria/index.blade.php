@extends('layouts.app')

@section('title', 'Auditoría del sistema')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Auditoría del sistema</h5>
                    <p class="text-muted mb-0">Registro de acciones realizadas por los usuarios con fecha, hora y detalles.</p>
                </div>
            </div>

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="usuario" class="form-control" placeholder="Buscar por usuario" value="{{ request('usuario') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="accion" class="form-control" placeholder="Buscar por acción" value="{{ request('accion') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Filtrar</button>
                </div>
            </form>

            <div class="d-flex justify-content-end gap-2 mb-3">
                <a href="{{ route('admin.auditoria.export.excel', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </a>
                <a href="{{ route('admin.auditoria.export.pdf', request()->query()) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Entidad</th>
                            <th>Detalles</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditorias as $registro)
                            <tr>
                                <td>{{ $registro->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $registro->user_name ?? 'Sistema' }}</td>
                                <td><span class="badge bg-primary">{{ $registro->action }}</span></td>
                                <td>{{ $registro->entity_type ?? '-' }} {{ $registro->entity_id ? '#'.$registro->entity_id : '' }}</td>
                                <td>{{ $registro->details ? json_decode($registro->details, true)['message'] ?? $registro->details : '-' }}</td>
                                <td>{{ $registro->ip_address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay registros de auditoría aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $auditorias->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
