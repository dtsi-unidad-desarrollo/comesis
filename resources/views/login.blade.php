@extends('layouts.index')

@section('title', 'COMESIS')

@section('content')
  <section class="section">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
            <a href="{{ route('login.index') }}" >
              <!--LOGO-->
              <img src="{{ asset('assets/img/logo.png') }}" alt="logo" id="logo">
            </a>
        </div>
        <div class="col-sm-6 col-xs-12 d-flex flex-column align-items-center justify-content-center">

          <!-- FORMULARIO -->
          <div class="card mb-1">

            <div class="card-body">

              <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4 text-dark">Ingrese a su cuenta</h5>
                <p class="text-center small text-primary">Ingrese su nombre de usuario y contraseña para iniciar sesión</p>
              </div>

              <form action="{{ route('login.store') }}" method="post" class="row g-3 needs-validation" novalidate>
              @csrf
              @method('post')
                <div class="col-12">
                  <label for="yourUsername" class="form-label">Nombre de Usuario</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text bg-primary text-white" id="inputGroupPrepend">@</span>
                    <input type="text" name="email" class="form-control" id="yourUsername" required>
                    <div class="invalid-feedback">Por favor, ingrese su nombre de usuario!</div>
                  </div>
                </div>

                <div class="col-12">
                  <label for="yourPassword" class="form-label">Contraseña</label>
                  <input type="password" name="password" class="form-control" id="yourPassword" required>
                  <div class="invalid-feedback">Por favor, ingrese su contraseña!</div>
                </div>

                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="rememberMe" value="true" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Acuérdate de mí</label>
                  </div>
                </div>
                <div class="col-12">
                  <button class="btn btn-primary w-100" type="submit">Entrar</button>
                </div>
              </form>

            </div>
          </div> <!-- Cierre de formulario -->

          @error('mensaje')
            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
              {{  $message }}
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @enderror

        </div>

      </div>
    </div>

  </section>
@endsection


    