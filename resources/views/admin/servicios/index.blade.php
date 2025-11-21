@extends('layouts.app')

@section('title', 'Servicios')

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
                <h2> Configurar servicios </h2>
            </div>
            <div class="col-sm-6 col-xs-12">
                @include('admin.servicios.partials.modalFormulario')
            </div>
            {{-- <div class="col-sm-6 col-xs-12">
                <form action="{{ route('admin.servicios.index') }}" method="post">
                    @csrf
                    @method('get')
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="filtro" 
                            placeholder="Filtrar (Por cédula o Por nombre)" 
                            aria-label="Filtrar"
                            aria-describedby="button-addon2" required>
                        <button class="btn btn-primary" type="submit" id="button-addon2">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div> --}}

            <div class="col-lg-12 table-responsive">
                <!-- Table with stripped rows -->

                <table class="table table-hover  bg-white mt-2">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th scope="col">Nombre del servicio</th>
                            <th scope="col">Horario de atención</th>
                            <th scope="col">Disponibilidad</th>
                            <th scope="col">Estatus</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->nombre }}</td>
                                <td>{{ $servicio->hora_inicio }} - {{ $servicio->hora_cierre }}</td>
                                <td>{{ number_format( $servicio->disponibilidad, 0, ',', '.') }} Comidas</td>
                                <td class=" {{ $servicio->estatus ? 'table-success' : 'table-danger'}} ">
                                    {{ $servicio->estatus ? 'ACTIVO' : 'INACTIVO'}}
                                </td>

                                <td>

                                    {{-- Boton modal de info del estudiante --}}
                                    {{-- @include('admin.servicios.partials.modaldialog')  --}}
                                    
                                    {{-- Boton editar --}}
                                    @include('admin.servicios.partials.modalFormularioEditar') 
                    

                                    {{-- Boton eliminar --}}
                                    @include('admin.servicios.partials.modal')
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>

                            <td colspan="7" class="text-center table-secondary">
                                Total de servicios: {{ $servicios->total() }} | 
                                <a href="{{ route('admin.servicios.index') }}"
                                   class="text-primary" >
                                    Ver todo
                                </a>
                                <br>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <!-- End Table with stripped rows -->
                <div class="col-xs-12 col-sm-6 ">
                    {{ $servicios->appends(['filtro' => $request->filtro])->links() }}
                </div>

            </div>


          
            
        </div>



    </section>
@endsection
