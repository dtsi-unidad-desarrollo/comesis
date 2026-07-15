@extends('layouts.index')

@section('title', 'COMESIS - Login')

@section('content')
    <section class="section min-vh-100 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container py-5 position-relative">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-7 col-xl-6">
                    <div class="card border-0 rounded-5 shadow-xl overflow-hidden" style="backdrop-filter: blur(8px); background: rgba(255, 255, 255, 0.95);">
                        <div class="row g-0">
                            <div class="col-12 bg-white p-4 p-sm-5 d-flex flex-column justify-content-center">
                                <div class="text-center mb-4 mb-sm-5">
                                    <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="img-fluid mb-3" style="max-width: 92px;">
                                    <h3 class="fw-bold mb-2">Iniciar Sesión</h3>
                                    <p class="text-muted mb-0">Ingresa tus credenciales para acceder al portal de COMESIS.</p>
                                </div>

                                <form action="{{ route('login.store') }}" method="post" class="row g-4 needs-validation" novalidate>
                                    @csrf
                                    @method('post')

                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label fw-semibold">Correo Electrónico</label>
                                        <div class="input-group rounded-3 shadow-sm border-0" style="overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
                                            <span class="input-group-text bg-light border-0" id="inputGroupPrepend">
                                                <i class="bi bi-envelope text-primary"></i>
                                            </span>
                                            <input type="text" name="email" class="form-control form-control-lg border-0 ps-0" id="yourUsername" placeholder="usuario@dominio.com" autocomplete="username" required autofocus>
                                            <div class="invalid-feedback">Por favor, ingrese su correo electrónico.</div>
                                        </div>
                                        @error('email')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label fw-semibold">Contraseña</label>
                                        <div class="input-group rounded-3 shadow-sm border-0" style="overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
                                            <span class="input-group-text bg-light border-0" id="passwordPrepend">
                                                <i class="bi bi-lock text-primary"></i>
                                            </span>
                                            <input type="password" name="password" class="form-control form-control-lg border-0 ps-0" id="yourPassword" placeholder="••••••••" autocomplete="current-password" required>
                                            <button class="btn btn-outline-secondary border-0" type="button" id="togglePassword">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <div class="invalid-feedback">Por favor, ingrese su contraseña.</div>
                                        </div>
                                        @error('password')
                                            <div class="form-text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="rememberMe" value="true" id="rememberMe">
                                                <label class="form-check-label text-muted" for="rememberMe">Recordar sesión</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold" type="submit" style="padding: 13px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>
                                            Acceder al Sistema
                                        </button>
                                    </div>
                                </form>

                                <div class="text-center mt-4">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Para soporte técnico contacta al administrador del sistema.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @error('mensaje')
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show mt-4 rounded-3 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ $message }}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <style>
            .shadow-xl {
                box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
            }
            .form-control:focus {
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
                border-color: #667eea;
            }
            .input-group-text {
                background: #f8f9fa !important;
                border: none !important;
            }
            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toggle = document.getElementById('togglePassword');
                if (toggle) {
                    toggle.addEventListener('click', function() {
                        var passwordInput = document.getElementById('yourPassword');
                        var icon = this.querySelector('i');
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