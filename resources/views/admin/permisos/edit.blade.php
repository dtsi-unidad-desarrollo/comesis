@extends('layouts.app')

@section('title', 'Editar Permiso')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

    <section class="section">
        {{-- Banner de Encabezado --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-key-fill me-1"></i> Modificar Permiso #{{ $permiso->id }}
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white font-monospace">{{ $permiso->nombre }}</h2>
                                <p class="mb-0 text-white-50">Configura el identificador o estatus de este permiso</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.permisos.index') }}" 
                                   class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    <span>Volver a la Lista</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario de Edición --}}
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.permisos.update', $permiso->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small mb-1">Identificador del Permiso</label>
                                <input type="text" name="nombre" class="form-control border-secondary-subtle font-monospace" 
                                       value="{{ old('nombre', $permiso->nombre) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary small mb-1">Estatus</label>
                                <select name="estatus" class="form-select border-secondary-subtle">
                                    <option value="1" {{ old('estatus', $permiso->estatus) == 1 ? 'selected' : '' }}>ACTIVO</option>
                                    <option value="0" {{ old('estatus', $permiso->estatus) == 0 ? 'selected' : '' }}>INACTIVO</option>
                                </select>
                            </div>

                            <div class="pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.permisos.index') }}" class="btn btn-light rounded-pill px-4">Cancelar</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
