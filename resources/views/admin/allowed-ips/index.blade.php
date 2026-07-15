@extends('layouts.app')

@section('title', 'Lista blanca de IPs')

@section('content')
<div class="container">
    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Lista blanca de IPs</h5>
                    <p class="text-muted mb-0">Administra las direcciones IP autorizadas para acceder al sistema.</p>
                </div>
            </div>

            <form action="{{ route('admin.allowed-ips.store') }}" method="POST" class="row g-2 mb-4">
                @csrf
                <div class="col-md-4">
                    <input type="text" name="ip_address" class="form-control" placeholder="Ej. 192.168.1.10" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="description" class="form-control" placeholder="Descripción (opcional)">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" type="submit">Agregar IP</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>IP</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ips as $ip)
                            <tr>
                                <td>{{ $ip->ip_address }}</td>
                                <td>{{ $ip->description ?? '-' }}</td>
                                <td>
                                    @if($ip->status)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-secondary">Inactiva</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.allowed-ips.update', $ip) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $ip->status ? 0 : 1 }}">
                                        <button class="btn btn-sm btn-outline-secondary" type="submit">
                                            {{ $ip->status ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.allowed-ips.destroy', $ip) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay IPs registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
