@extends('layouts.app')

@section('title', 'Historial')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

    {{-- respuesta de validadciones --}}
    <div class="col-12">
        @if ($errors->any())
            <div class="alert alert-danger text-start">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <section class="section">
        <div class="row">

            <div class="col-12">
                <h2> Historial de entradas </h2>
            </div>
            <div class="col-sm-6 col-xs-12">
                @include('admin.entradas.partials.modalFormularioReporte')
            </div>
            <div class="col-xs-12">
                <form action="{{ route('admin.entradas.index') }}" method="post">
                    @csrf
                    @method('GET')
                    <div class="input-group mb-3">
                        <select name="servicio" id="servicio" class="form-select">
                            <option value="0">Seleccione servicio</option>
                            @foreach ($servicios as $servicio)
                                @if ( $request->servicio == $servicio->nombre)
                                    <option value="{{ $servicio->nombre }}" selected>{{ $servicio->nombre }}</option>
                                @endif
                                <option value="{{ $servicio->nombre }}">{{ $servicio->nombre }}</option>
                            @endforeach
                        </select>

                        <input type="date" class="form-control" name="fecha" aria-label="fecha"
                            value="{{ $request->fecha ? $request->fecha : '' }}"
                            aria-describedby="button-addon2">

                        <input type="text" class="form-control" name="filtro"
                            placeholder="Filtrar (Por cédula o Por nombre)" aria-label="Filtrar"
                            value="{{ $request->filtro ? $request->filtro : '' }}"
                            aria-describedby="button-addon2">


                        <input type="hidden" name="activo" value="true">
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-12 table-responsive">
                <!-- Table with stripped rows -->

                <table class="table table-hover  bg-white mt-2">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th scope="col">Nombres y apellido</th>
                            <th scope="col">cedula</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">comida</th>
                            <th scope="col">fecha</th>
                            <th scope="col">hora</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($entradas as $entrada)
                            <tr>
                                <td>
                                    {{ $entrada->nombres }}
                                    {{ $entrada->apellidos }}
                                </td>
                                <td>{{ $entrada->nacionalidad . '-' . $entrada->cedula }}</td>
                                <td>{{ $entrada->tipo_comensal }}</td>
                                <td class="">{{ $entrada->comida }}</td>
                                <td>{{ $entrada->fecha }}</td>
                                <td>{{ $entrada->hora }}</td>
                                <td>
                                    @if (Auth::user()->rol <= 2)
                                        @include('admin.entradas.partials.modal')
                                    @endif

                                    @include('admin.entradas.partials.modaldialog')
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-center table-secondary">
                                Total de entradas: {{ $entradas->total() }} |
                                <a href="{{ route('admin.entradas.index') }}" class="text-primary">
                                    Ver todo
                                </a>
                                <br>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <!-- End Table with stripped rows -->
                <div class="col-xs-12 col-sm-6 ">
                    {{ $entradas->appends(['filtro' => $request->filtro, 'servicio' => $request->servicio, 'fecha' => $request->fecha, 'activo' => $request->activo])->links() }}
                </div>

            </div>




        </div>



    </section>
@endsection
