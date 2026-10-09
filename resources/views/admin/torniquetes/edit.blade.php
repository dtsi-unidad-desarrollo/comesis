@extends('layouts.app')

@section('title', 'Editar Torniquete')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

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
                                    <i class="bi bi-door-open-fill me-1"></i> Dispositivos Físicos
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Editar Torniquete: {{ $torniquete->nombre }}</h2>
                                <p class="mb-0 text-white-50">Modifica los parámetros de red y estado del torniquete</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.torniquetes.index') }}" 
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

        {{-- Formulario --}}
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('admin.torniquetes.update', $torniquete->id) }}">
                            @method('PUT')
                            @include('admin.torniquetes._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
