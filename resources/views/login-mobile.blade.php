@extends('layouts.index')

@section('title', 'COMESIS - Iniciar Sesión')

@section('content')
    <section class="section min-vh-100 d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden py-4 px-3"
        style="background: linear-gradient(145deg, #0b347b 0%, #05193d 100%);">

        <!-- Destellos sutiles de fondo -->
        <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none"
            style="background: radial-gradient(circle at 50% 20%, rgba(13, 110, 253, 0.3) 0%, transparent 65%);">
        </div>

        <div class="container position-relative z-index-1" style="max-width: 460px;">
            <!-- Cabecera Móvil: Logo y Palabra COMESIS al lado -->
            <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
                <div class="d-inline-flex align-items-center justify-content-center p-2 rounded-4 flex-shrink-0"
                    style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.16); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo COMESIS" class="img-fluid"
                        style="height: 56px; width: auto; object-fit: contain; filter: drop-shadow(0 6px 14px rgba(0, 0, 0, 0.4));">
                </div>
                <div>
                    <h1 class="h3 fw-bold text-white mb-1" style="letter-spacing: -0.5px; line-height: 1.1;">COMESIS</h1>
                    <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-25 rounded-pill px-2.5 py-1 text-uppercase" style="letter-spacing: 0.6px; font-size: 10px;">
                        <i class="bi bi-shield-check me-1"></i> Control de Comedor
                    </span>
                </div>
            </div>

            <!-- Tarjeta de Acceso -->
            <div class="card border-0 rounded-5 overflow-hidden"
                style="backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.98); box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.65), 0 0 35px rgba(13, 110, 253, 0.25); border: 1px solid rgba(255, 255, 255, 0.15) !important;">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h2 class="h4 fw-bold text-dark mb-1">Iniciar Sesión</h2>
                        <p class="text-muted small mb-0">Ingresa tus credenciales para acceder al sistema</p>
                    </div>

                    {{-- Alerta de expiración de sesión por inactividad --}}
                    @if(request()->has('expired'))
                        <div class="alert alert-warning border border-warning-subtle rounded-4 shadow-sm mb-3 py-2 px-3 small d-flex align-items-center" role="alert">
                            <i class="bi bi-clock-history fs-4 me-2 text-warning flex-shrink-0"></i>
                            <div>
                                <strong>Sesión expirada:</strong> Tu sesión finalizó por inactividad. Inicia sesión nuevamente.
                            </div>
                        </div>
                    @endif

                    {{-- Alerta de error de credenciales / acceso --}}
                    @error('mensaje')
                        <div class="alert alert-danger bg-danger-subtle text-danger border border-danger-subtle rounded-4 shadow-sm mb-3 py-2 px-3 small d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-octagon-fill fs-5 me-2 flex-shrink-0"></i>
                            <div>{{ $message }}</div>
                        </div>
                    @enderror

                    <form action="{{ route('login.store') }}" method="POST" class="row g-3 needs-validation" novalidate>
                        @csrf

                        {{-- Campo Usuario / Email --}}
                        <div class="col-12">
                            <label for="yourUsername" class="form-label fw-semibold text-secondary small mb-1">
                                Usuario o Correo Electrónico
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input type="text" name="email"
                                    class="form-control form-control-lg border-secondary-subtle fs-6" id="yourUsername"
                                    placeholder="usuario / correo@unellez.edu.ve" value="{{ old('email') }}"
                                    autocomplete="username" required autofocus inputmode="email">
                                <div class="invalid-feedback">Por favor, ingrese su usuario o correo.</div>
                            </div>
                            @error('email')
                                <div class="form-text text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Contraseña --}}
                        <div class="col-12">
                            <label for="yourPassword" class="form-label fw-semibold text-secondary small mb-1">
                                Contraseña
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="password"
                                    class="form-control form-control-lg border-secondary-subtle fs-6" id="yourPassword"
                                    placeholder="••••••••" autocomplete="current-password" required>
                                <button class="btn btn-light border border-secondary-subtle border-start-0 text-secondary"
                                    type="button" id="togglePassword" title="Mostrar/Ocultar contraseña" aria-label="Mostrar u ocultar contraseña">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">Por favor, ingrese su contraseña.</div>
                            </div>
                            @error('password')
                                <div class="form-text text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Recordar sesión --}}
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rememberMe" value="true" id="rememberMe">
                                <label class="form-check-label text-muted small" for="rememberMe">
                                    Mantener sesión iniciada
                                </label>
                            </div>
                        </div>

                        {{-- Botón de Acceso Principal --}}
                        <div class="col-12 mt-3">
                            <button class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center py-3"
                                type="submit"
                                style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); border: none; box-shadow: 0 8px 20px rgba(13, 110, 253, 0.35); transition: all 0.2s ease;">
                                <i class="bi bi-box-arrow-in-right me-2 fs-5"></i>
                                Acceder al Sistema
                            </button>
                        </div>
                    </form>

                    {{-- Pie del Formulario Móvil con raya finita y sutil --}}
                    <div class="text-center mt-4 pt-3" style="border-top: 1px solid rgba(0, 0, 0, 0.06);">
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-shield-check text-primary me-1"></i>
                            Acceso restringido &bull; UNELLEZ
                        </small>
                        <small class="text-muted" style="font-size: 11px;">
                            DTSI &bull; Versión 2.5
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .pointer-events-none {
                pointer-events: none;
            }

            .z-index-1 {
                z-index: 1;
            }

            .form-control:focus {
                border-color: #0d6efd !important;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
            }

            .btn-primary:active,
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 25px rgba(13, 110, 253, 0.45) !important;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggle = document.getElementById('togglePassword');
                if (toggle) {
                    toggle.addEventListener('click', function() {
                        const passwordInput = document.getElementById('yourPassword');
                        const icon = this.querySelector('i');

                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            icon.classList.remove('bi-eye');
                            icon.classList.add('bi-eye-slash');
                        } else {
                            passwordInput.type = 'password';
                            icon.classList.remove('bi-eye-slash');
                            icon.classList.add('bi-eye');
                        }
                    });
                }
            });
        </script>
    </section>
@endsection