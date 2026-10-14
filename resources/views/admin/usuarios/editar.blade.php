@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
    @if (session('mensaje'))
        <div class="alert alert-danger alert-dismissible fade show text-start shadow-sm mb-4 rounded-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                <div>{{ session('mensaje') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                    <i class="bi bi-pencil-square me-1"></i> Modificar Cuenta #{{ $usuario->id }}
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">{{ $usuario->nombre }}</h2>
                                <p class="mb-0 text-white-50">Edita los datos de acceso, rol o actualiza la contraseña del usuario</p>
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

        {{-- Formulario de Edición --}}
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.users.update', $usuario->id) }}" method="post"
                            enctype="multipart/form-data" class="row g-4 needs-validation" novalidate>
                            @csrf
                            @method('put')

                            <!-- Foto Actual y Vista Previa -->
                            <div class="col-12 text-center pb-2">
                                @php
                                    $fotoValida = $usuario->foto && file_exists(public_path($usuario->foto));
                                @endphp
                                @if ($fotoValida)
                                    <img src="{{ asset($usuario->foto) }}" class="rounded-circle shadow-sm border border-3 border-light mb-2" 
                                         style="width: 90px; height: 90px; object-fit: cover;" alt="Foto">
                                @else
                                    <div class="rounded-circle bg-primary text-white fw-bold d-inline-flex align-items-center justify-content-center shadow-sm mb-2"
                                         style="width: 80px; height: 80px; font-size: 32px;">
                                        {{ strtoupper(substr(trim($usuario->nombre ?? 'U'), 0, 1)) }}
                                    </div>
                                @endif
                                <h5 class="fw-bold mb-0 text-dark">{{ $usuario->nombre }}</h5>
                                <span class="badge bg-secondary-subtle text-primary font-monospace">{{ $usuario->email }}</span>
                            </div>

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
                                           id="emailAcceso" value="{{ $usuario->email ?? old('email') }}" required>
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
                                           id="nombreCompleto" value="{{ $usuario->nombre ?? old('nombre') }}" required>
                                </div>
                                @error('nombre')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Rol del Usuario -->
                            <div class="col-md-6">
                                <label for="rol" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-shield-lock text-primary me-1"></i> Rol en el Sistema
                                </label>
                                <select name="rol" id="rol" class="form-select border-secondary-subtle @error('rol') is-invalid @enderror" required>
                                    <option value="" disabled>Seleccione un rol...</option>
                                    @foreach ($roles as $rol)
                                        @php
                                            $userRolId = is_object($usuario->rol) ? ($usuario->rol->id ?? 0) : (int)$usuario->rol;
                                            $selected = (old('rol') == $rol->id) || (!old('rol') && $userRolId == $rol->id);
                                        @endphp
                                        <option value="{{ $rol->id }}" {{ $selected ? 'selected' : '' }}>
                                            {{ $rol->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Subir Nueva Foto -->
                            <div class="col-md-6">
                                <label for="foto" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-image text-primary me-1"></i> Cambiar Foto (Opcional)
                                </label>
                                <input type="file" name="file" class="form-control border-secondary-subtle @error('file') is-invalid @enderror" 
                                       id="foto" accept="image/*">
                                @error('file')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nueva Contraseña (Protegida) -->
                            <div class="col-12">
                                <label for="inputCedula" class="form-label fw-semibold text-secondary small mb-1">
                                    <i class="bi bi-key text-primary me-1"></i> Nueva Contraseña (Dejar bloqueada para no cambiarla)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control bg-light border-secondary-subtle"
                                           id="inputCedula" placeholder="Haga clic en el lápiz para activar el cambio"
                                           value="{{ old('password') }}" disabled readonly>
                                    <button type="button" class="btn btn-warning" id="activarEdicionDeCedula" title="Activar cambio de contraseña">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-pill px-4">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                    <i class="bi bi-check-circle-fill me-2"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/js/usuarios/editar.js') }}"></script>
@endsection
