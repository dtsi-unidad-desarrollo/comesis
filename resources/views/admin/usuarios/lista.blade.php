@extends('layouts.app')

@section('title', 'Lista de Usuario')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>
    <div id="alert"></div>
    <section class="section profile">
        <div class="row">
            <div class="col-sm-12">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary fs-5 mb-3">
                    <i class="bi bi-people "></i>
                    Nuevo Usuario
                </a>
            </div>
            @foreach ($usuarios as $usuario)
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="{{ $usuario->foto }}" alt="Profile" class="rounded-circle">
                            <h2>{{ $usuario->nombre }}</h2>
                            <h5 class="fs-6">{{ $usuario->rol->nombre ?? '' }}</h5>
                            <div class="social-links mt-2">

                                <a href="{{ route('admin.users.edit', $usuario->id) }}" class=" ">
                                    <i class="bi bi-pencil fs-3 text-warning"></i>
                                </a>

                                @include('admin.usuarios.partials.modal')

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </section>

@endsection
