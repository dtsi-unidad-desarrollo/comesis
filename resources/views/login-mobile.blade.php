@extends('layouts.index')

@section('title', 'COMESIS - Login')

@section('content')
    <section class="section min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(145deg, #f5f7ff 0%, #ecf1ff 45%, #ffffff 100%);">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8">
                    <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="img-fluid mb-3" style="max-width: 80px;">
                                <h3 class="fw-bold mb-1">Inicia sesión</h3>
                                <p class="text-muted mb-0">Bienvenido de nuevo</p>
                            </div>

                            <form action="{{ route('login.store') }}" method="post" class="row g-3 needs-validation" novalidate>
                                @csrf
                                @method('post')

                                <div class="col-12">
                                    <label for="yourUsername" class="form-label">Usuario</label>
                                    <div class="input-group rounded-3 shadow-sm overflow-hidden">
                                        <span class="input-group-text bg-primary text-white border-0" id="inputGroupPrepend">@</span>
                                        <input type="text" name="email" class="form-control form-control-lg border-0" id="yourUsername" placeholder="usuario@dominio.com" autocomplete="username" required>
                                        <div class="invalid-feedback">Por favor, ingrese su nombre de usuario.</div>
                                    </div>
                                    @error('email')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="yourPassword" class="form-label">Contraseña</label>
                                    <input type="password" name="password" class="form-control form-control-lg shadow-sm rounded-3 border-0" id="yourPassword" placeholder="Contraseña" autocomplete="current-password" required>
                                    <div class="invalid-feedback">Por favor, ingrese su contraseña.</div>
                                    @error('password')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="rememberMe" value="true" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Acuérdate de mí</label>
                                    </div>
                                    <button class="btn btn-primary btn-lg w-100" type="submit">Entrar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @error('mensaje')
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show mt-4" role="alert">
                            {{ $message }}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </section>
@endsection