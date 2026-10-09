@extends('layouts.app')

@section('title', 'Servicios y Turnos')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

    {{-- Respuesta de validaciones --}}
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-start shadow-sm mb-4 rounded-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                <div>
                    <strong>¡Atención!</strong> Por favor verifica los siguientes campos:
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        {{-- Banner Principal --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-cup-hot-fill me-1"></i> Configuración de Turnos
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Servicios y Turnos de Comedor</h2>
                                <p class="mb-0 text-white-50">Administra los horarios de apertura, cierre y cupos de bandejas para cada turno</p>
                            </div>
                            <div>
                                @include('admin.servicios.partials.modalFormulario')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjetas KPI de Resumen --}}
        @php
            $totalServicios = $servicios->total();
            $serviciosActivos = $servicios->where('estatus', 1)->count();
            $capacidadTotal = $servicios->where('estatus', 1)->sum('disponibilidad');
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-primary">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-cup-straw fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Turnos Registrados</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalServicios }}</h3>
                            <small class="text-primary fw-semibold">Turnos configurados</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-success">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-check2-circle fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Servicios Activos</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $serviciosActivos }}</h3>
                            <small class="text-success fw-semibold">Habilitados para control</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-info">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-box-seam fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Capacidad Diaria</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($capacidadTotal) }}</h3>
                            <small class="text-info fw-semibold">Bandejas autorizadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Servicios --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ $servicios->total() }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Turnos Disponibles</h5>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Servicio / Turno</th>
                                    <th scope="col">Horario de Atención</th>
                                    <th scope="col" class="text-center">Disponibilidad Máxima</th>
                                    <th scope="col" class="text-center">Estatus</th>
                                    <th scope="col" class="text-center pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($servicios as $servicio)
                                    <tr>
                                        {{-- Nombre con Avatar --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                                     style="width: 40px; height: 40px; background-color: #0d6efd; font-size: 16px;">
                                                    <i class="bi bi-cup-hot"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">
                                                        {{ $servicio->nombre }}
                                                    </div>
                                                    <small class="text-muted">
                                                        Turno #{{ $servicio->id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Horario --}}
                                        <td>
                                            <span class="badge bg-secondary-subtle text-primary font-monospace px-3 py-1 fs-7 border">
                                                <i class="bi bi-clock me-1"></i> {{ $servicio->hora_inicio }} - {{ $servicio->hora_cierre }}
                                            </span>
                                        </td>

                                        {{-- Disponibilidad --}}
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1 fw-bold fs-7">
                                                {{ number_format($servicio->disponibilidad, 0, ',', '.') }} Bandejas
                                            </span>
                                        </td>

                                        {{-- Estatus --}}
                                        <td class="text-center">
                                            @if ($servicio->estatus)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> ACTIVO
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                                    <i class="bi bi-x-circle-fill me-1"></i> INACTIVO
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-center pe-4">
                                            <div class="d-inline-flex gap-1 align-items-center justify-content-center">
                                                {{-- Botón editar --}}
                                                @include('admin.servicios.partials.modalFormularioEditar') 

                                                {{-- Botón eliminar / desactivar --}}
                                                @include('admin.servicios.partials.modal')
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-cup-hot text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No hay servicios registrados</h5>
                                                <p class="text-muted small mb-0">Registra un nuevo turno para habilitar el comedor.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer de Tabla y Paginación --}}
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="text-muted small">
                                Mostrando <strong>{{ $servicios->firstItem() ?? 0 }}</strong> a <strong>{{ $servicios->lastItem() ?? 0 }}</strong> de <strong>{{ $servicios->total() }}</strong> servicios
                            </div>
                            <div>
                                {{ $servicios->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
