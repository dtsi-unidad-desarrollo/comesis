@extends('layouts.index')

@section('title', 'COMESIS - Panel de Control')

@section('content')
    <section class="section min-vh-100 d-flex align-items-center justify-content-center position-relative overflow-hidden"
        style="background: linear-gradient(135deg, #01156e 0%, #bd95e0 100%);">
        <!-- Elementos decorativos de fondo -->
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background: radial-gradient(circle at 20% 80%, rgba(30, 27, 165, 0.3) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%), radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);">
        </div>

        <div class="container py-5 position-relative">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="card border-0 rounded-5 shadow-xl overflow-hidden"
                        style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                        <div class="row g-0">
                            <!-- Panel lateral con información -->
                            <div
                                class="col-12 col-lg-6 bg-gradient-primary text-white d-flex flex-column justify-content-center p-5 position-relative">

                                <div class="position-relative z-index-1">
                                    <div class="mb-4">
                                        <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="img-fluid mb-4"
                                            style="max-width: 200px; ">
                                    </div>

                                    <h1 class="display-6 fw-bold mb-3">Sistema de Gestión</h1>
                                    <h2 class="h4 fw-light mb-4 opacity-90">Comedor Institucional</h2>

                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                            <span>Control total de usuarios y permisos</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                            <span>Gestión eficiente de entradas y salidas</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                            <span>Reportes detallados y estadísticas</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                            <span>Interfaz intuitiva y moderna</span>
                                        </div>
                                    </div>

                                    <div class="mt-auto">
                                        <p class="mb-0 small opacity-75">
                                            <i class="bi bi-shield-lock me-2"></i>
                                            Acceso seguro y protegido
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel del formulario -->
                            <div class="col-12 col-lg-6 bg-white p-5 d-flex flex-column justify-content-center">
                                <div class="mb-5">
                                    <h3 class="fw-bold mb-2">Iniciar Sesión</h3>
                                    <p class="text-muted mb-0">Ingresa tus credenciales para acceder al panel administrativo
                                    </p>
                                </div>

                                <form action="{{ route('login.store') }}" method="post" class="row g-4 needs-validation"
                                    novalidate>
                                    @csrf
                                    @method('post')

                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label fw-semibold">Correo Electrónico</label>
                                        <div class="input-group rounded-3 shadow-sm border-0"
                                            style="overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                            <span class="input-group-text bg-light border-0" id="inputGroupPrepend">
                                                <i class="bi bi-envelope text-primary"></i>
                                            </span>
                                            <input type="text" name="email"
                                                class="form-control form-control-lg border-0 ps-0" id="yourUsername"
                                                placeholder="usuario@dominio.com" autocomplete="username" required>
                                            <div class="invalid-feedback">Por favor, ingrese su correo electrónico.</div>
                                        </div>
                                        @error('email')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label fw-semibold">Contraseña</label>
                                        <div class="input-group rounded-3 shadow-sm border-0"
                                            style="overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                            <span class="input-group-text bg-light border-0" id="passwordPrepend">
                                                <i class="bi bi-lock text-primary"></i>
                                            </span>
                                            <input type="password" name="password"
                                                class="form-control form-control-lg border-0 ps-0" id="yourPassword"
                                                placeholder="••••••••" autocomplete="current-password" required>
                                            <button class="btn btn-outline-secondary border-0" type="button"
                                                id="togglePassword">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <div class="invalid-feedback">Por favor, ingrese su contraseña.</div>
                                        </div>
                                        @error('password')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="rememberMe"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label text-muted" for="rememberMe">
                                                    Recordar sesión
                                                </label>
                                            </div>
                                            <a href="#"
                                                class="text-decoration-none text-primary fw-semibold">¿Olvidaste tu
                                                contraseña?</a>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold" type="submit"
                                            style="padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>
                                            Acceder al Sistema
                                        </button>
                                    </div>
                                </form>

                                <div class="text-center mt-4">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Para soporte técnico contacta al administrador del sistema
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @error('mensaje')
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show mt-4 rounded-3 shadow-sm"
                            role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ $message }}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #2d4cd3 0%, #764ba2 100%);
        }

        .shadow-xl {
            box-shadow: 0 20px 40px rgba(240, 228, 228, 0.1) !important;
        }

        .z-index-1 {
            z-index: 1;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            border-color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(45, 75, 209, 0.5);
        }

        .input-group-text {
            background: #f8f9fa !important;
            border: none !important;
        }

        @media (max-width: 991.98px) {
            .bg-gradient-primary {
                min-height: 300px;
            }
        }
    </style>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
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
    </script>
@endsection
