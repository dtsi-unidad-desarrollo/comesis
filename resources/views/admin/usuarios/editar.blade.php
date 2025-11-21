@extends('layouts.app')

@section('title', 'Editar Usuario')


@section('content')
    <div class="container">

        @if (session('mensaje'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('mensaje') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        

        <section class="section register d-flex flex-column align-items-center justify-content-center ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class=" col-sm-8 d-flex flex-column align-items-center justify-content-center">
                        


                        <div class="card ">

                            <div class="card-body">

                                <div class=" pb-2">
                                    <h5 class="card-title text-center pb-0 fs-2">Editar Usuario</h5>
                                    <p class="text-center text-danger small">Rellene todos los campos</p>
                                </div>

                                <form action="{{ route('admin.users.update', $usuario->id) }}" method="post"
                                    enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                                    @csrf
                                    @method('put')
                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Email de acceso</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend">@</span>
                                            <input type="text" name="email" class="form-control" id="yourUsername"
                                                placeholder="Ingrese su email" value="{{ $usuario->email ?? old('email') }}"
                                                required>
                                            <div class="invalid-feedback">Por favor ingrese su nombre de usuario! </div>
                                            @error('email')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Nombre completo</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-white bg-primary"
                                                id="inputGroupPrepend">@</span>
                                            <input type="text" name="nombre" class="form-control" id="yourUsername"
                                                placeholder="Ingrese su nombre de usuario"
                                                value="{{ $usuario->nombre ?? old('nombre') }}" required>
                                            <div class="invalid-feedback">Por favor ingrese su nombre completo! </div>
                                        </div>
                                        @error('nombre')
                                            <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>


                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Nueva Contrseña</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control bg-muted"
                                                id="inputCedula" placeholder="Ingrese nueva contraseña"
                                                value="{{ old('password') }}" disabled readonly required>
                                            <button type="button" class="btn btn-warning" id="activarEdicionDeCedula">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">Por favor ingrese su Nueva contraseña!</div>
                                        @error('password')
                                            <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="yourName" class="form-label">Rol del usuario</label>
                                        <select name="rol" id="rol" class="form-select" required>
                                            <option value="">Seleccione un rol</option>
                                            @foreach ($roles as $rol)
                                                @if (old('rol') == $rol->id)
                                                    <option value="{{ $rol->id }}" selected>{{ $rol->nombre }}
                                                    </option>
                                                @endif

                                                @isset($usuario->rol)
                                                    @if ($usuario->rol == $rol->id)
                                                        <option value="{{ $rol->id }}" selected>{{ $rol->nombre }}
                                                        </option>
                                                    @endif
                                                @endisset

                                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                            @endforeach


                                        </select>
                                        <div class="invalid-feedback">Por favor, Seleccione el Rol del usuario!</div>
                                        @error('rol')
                                            <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-12">
                                        <label for="foto" class="form-label">Subir Foto (Opcional)</label>
                                        <input type="file" name="file" class="form-control " id="foto">
                                        <div class="invalid-feedback">Ingrese una imagen valida</div>
                                        @error('file')
                                            <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="foto" class="form-label">Foto Actual</label>
                                        <img src="{{ $usuario->foto }}" class="rounded mx-auto d-block" width="200"
                                            alt="foto">
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger">Volver</a>

                                        <button class="btn btn-outline-primary" type="submit">Guardar cambios</button>
                                    </div>

                                </form>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <script src="{{ asset('assets/js/usuarios/editar.js') }}"></script>
        </section>
    @endsection
