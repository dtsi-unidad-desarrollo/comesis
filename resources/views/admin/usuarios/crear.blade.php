@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
    @isset($respuesta)
        @include('partials.alert')
    @endisset

    <div id="alert"></div>

    {{-- Respuesta de validaciones --}}
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-start shadow-sm mb-4 rounded-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                <div>
                    <strong>¡Atención!</strong> Por favor verifica los campos con error:
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
        {{-- Banner de Encabezado --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-person-plus-fill me-1"></i> Nueva Cuenta
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Registrar Usuario</h2>
                                <p class="mb-0 text-white-50">Crea un nuevo operador con credenciales y permisos en el sistema</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.index') }}" 
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

        {{-- Formulario de Registro --}}
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data"
                            class="row g-4 needs-validation" novalidate>
                            @csrf

                            <!-- Email / Usuario de acceso -->
                            <div class="col-md-6">
                                <label for="emailAcceso" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-at text-primary me-1"></i> Usuario / Email de Acceso
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" name="email" class="form-control border-secondary-subtle @error('email') is-invalid @enderror" 
                                           id="emailAcceso" placeholder="Ej: operador1" value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nombre Completo -->
                            <div class="col-md-6">
                                <label for="nombreCompleto" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-card-text text-primary me-1"></i> Nombre Completo
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <input type="text" name="nombre" class="form-control border-secondary-subtle @error('nombre') is-invalid @enderror" 
                                           id="nombreCompleto" placeholder="Ej: Juan Pérez" value="{{ old('nombre') }}" required>
                                </div>
                                @error('nombre')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Rol del Usuario -->
                            <div class="col-md-6">
                                <label for="rolUsuario" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-shield-lock text-primary me-1"></i> Rol en el Sistema
                                </label>
                                <select name="rol" class="form-select border-secondary-subtle @error('rol') is-invalid @enderror" id="rolUsuario" required>
                                    <option value="" disabled selected>Seleccione un rol...</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}" {{ old('rol') == $rol->id ? 'selected' : '' }}>
                                            {{ $rol->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Foto de Perfil -->
                            <div class="col-md-6">
                                <label for="fotoPerfil" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-image text-primary me-1"></i> Foto de Perfil (Opcional)
                                </label>
                                <input type="file" name="file" class="form-control border-secondary-subtle @error('file') is-invalid @enderror" 
                                       id="fotoPerfil" accept="image/*">
                                @error('file')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contraseña -->
                            <div class="col-md-6">
                                <label for="passwordUser" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-key text-primary me-1"></i> Contraseña
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control border-secondary-subtle @error('password') is-invalid @enderror" 
                                           id="passwordUser" placeholder="Mínimo 6 caracteres" required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="col-md-6">
                                <label for="passwordConfirm" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-lock-fill text-primary me-1"></i> Confirmar Contraseña
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-shield-check"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" class="form-control border-secondary-subtle" 
                                           id="passwordConfirm" placeholder="Repite la contraseña" required>
                                </div>
                            </div>

                            <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-pill px-4">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                    <i class="bi bi-check-circle-fill me-2"></i> Guardar Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
