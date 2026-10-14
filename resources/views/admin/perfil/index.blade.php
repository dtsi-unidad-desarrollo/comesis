@extends('layouts.app')

@section('title', 'Mi Perfil y Seguridad')

@section('content')
<div class="container py-2">
    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    {{-- Banner Superior --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                 style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                @if(Auth::user()->foto && file_exists(public_path(Auth::user()->foto)))
                                    <img src="{{ asset(Auth::user()->foto) }}" alt="Foto de perfil" class="rounded-circle border border-3 border-white shadow" width="70" height="70" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-white text-primary border border-3 border-white shadow d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 70px; height: 70px;">
                                        {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-1" style="width: 14px; height: 14px;" title="En línea"></span>
                            </div>
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-1 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-person-badge me-1"></i> Seguridad de Cuenta
                                </span>
                                <h2 class="h4 fw-bold mb-0 text-white">{{ $usuario->nombre }}</h2>
                                <p class="mb-0 text-white-50 small">{{ $usuario->email }} &bull; Rol: {{ $usuario->isRoot() ? 'Super Administrador' : ($usuario->isAdmin() ? 'Administrador' : ($usuario->role->nombre ?? 'Operador')) }}</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.panel.index') }}" class="btn btn-outline-light rounded-pill px-4 shadow-sm fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Volver al Panel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Columna 1: Actualizar Datos de Perfil y Clave --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                        <i class="bi bi-pencil-square text-primary me-2"></i> Datos de Acceso
                    </h5>
                    <p class="text-muted small mb-0">Actualiza tu nombre de usuario, correo electrónico o credenciales</p>
                </div>
                <div class="card-body p-4 pt-1">
                    <form action="{{ route('admin.perfil.update') }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Nombre Completo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="nombre" class="form-control border-secondary-subtle" value="{{ old('nombre', $usuario->nombre) }}" required>
                            </div>
                            @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Usuario / Correo Electrónico <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="text" name="email" class="form-control border-secondary-subtle" value="{{ old('email', $usuario->email) }}" required>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 pt-2">
                            <hr class="text-secondary-subtle my-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-key text-primary me-1"></i> Cambio de Contraseña</h6>
                                <span class="badge bg-light text-muted border small">Opcional</span>
                            </div>
                            <p class="text-muted small mb-3">Deja estos campos en blanco si deseas mantener tu contraseña actual. Al cambiarla, se cerrarán automáticamente las sesiones en otros dispositivos.</p>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary small mb-1">Contraseña Actual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="current_password" class="form-control border-secondary-subtle" placeholder="Requerida solo si vas a cambiar tu clave">
                            </div>
                            @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Nueva Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control border-secondary-subtle" placeholder="Mínimo 6 caracteres">
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Confirmar Nueva Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                <input type="password" name="password_confirmation" class="form-control border-secondary-subtle" placeholder="Repite la nueva contraseña">
                            </div>
                        </div>

                        <div class="col-12 pt-3 border-top d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Columna 2: Monitor de Sesiones Activas de este Usuario --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                            <i class="bi bi-display text-primary me-2"></i> Sesiones Activas
                        </h5>
                        <p class="text-muted small mb-0">Dispositivos con acceso a tu cuenta</p>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-7">
                        {{ count($sessions) }} activa{{ count($sessions) > 1 ? 's' : '' }}
                    </span>
                </div>
                <div class="card-body p-4 pt-1">
                    <div class="list-group list-group-flush mb-3">
                        @forelse($sessions as $session)
                            @php
                                $isCurrent = ($session->id === $currentSessionId);
                                $lastActive = \Carbon\Carbon::createFromTimestamp($session->last_activity);
                                $isMobile = preg_match('/mobile|android|iphone|ipad/i', $session->user_agent ?? '');
                            @endphp
                            <div class="list-group-item px-0 py-3 border-bottom {{ $isCurrent ? 'bg-light bg-opacity-50 rounded-3 p-2' : '' }}">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                        <i class="bi {{ $isMobile ? 'bi-phone' : 'bi-laptop' }} fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                            <span class="fw-bold text-dark small">
                                                {{ $session->ip_address }}
                                            </span>
                                            @if($isCurrent)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle small">
                                                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Este Dispositivo
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border small">
                                                    Otro Dispositivo
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-muted small mb-1 text-truncate" style="max-width: 280px;" title="{{ $session->user_agent }}">
                                            {{ Str::limit($session->user_agent, 45) }}
                                        </p>
                                        <div class="text-muted" style="font-size: 11px;">
                                            <i class="bi bi-clock me-1"></i> Última actividad: 
                                            <strong>{{ $lastActive->diffForHumans() }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">
                                <i class="bi bi-shield-slash fs-3 d-block mb-2"></i>
                                No se encontraron sesiones registradas.
                            </div>
                        @endforelse
                    </div>

                    @if(count($sessions) > 1)
                        <form action="{{ route('admin.perfil.revokeOtherSessions') }}" method="POST" onsubmit="return confirm('¿Estás seguro de cerrar sesión en todos los demás dispositivos?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión en Otros Dispositivos
                            </button>
                        </form>
                    @else
                        <div class="alert alert-light border small text-muted mb-0 rounded-3">
                            <i class="bi bi-shield-check text-success me-1"></i> Tu cuenta solo está activa en este dispositivo.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
