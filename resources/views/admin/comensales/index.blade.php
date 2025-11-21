@extends('layouts.app')

@section('title', 'Comensales')

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
                <h2> Lista de comensales </h2>
            </div>
            <div class="col-sm-6 col-xs-12">
                @include('admin.comensales.partials.modalFormulario')

                
                {{-- @include('admin.comensales.partials.modalSincronizardata') --}}
            </div>
            <div class="col-sm-6 col-xs-12">
                <form action="{{ route('admin.comensales.index') }}" method="post">
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
            </div>

            <div class="col-lg-12 table-responsive">
                <!-- Table with stripped rows -->

                <table class="table table-hover  bg-white mt-2">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th scope="col">#</th>
                            <th scope="col">Nombres</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Cédula</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Estatus</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($comensales as $comensal)
                            <tr>
                                <td scope="row">{{ $comensal->id }}</td>
                                <td>{{ $comensal->nombres }}</td>
                                <td>{{ $comensal->apellidos }}</td>
                                <td>{{$comensal->nacionalidad . '-' . $comensal->cedula }}</td>
                                <td class="table-warning">{{ $comensal->tipo_comensal }}</td>
                                <td class=" {{ $comensal->estatus ? 'table-success' : 'table-danger'}} ">
                                    {{ $comensal->estatus ? 'ACTIVO' : 'INACTIVO'}}
                                </td>

                                <td>

                                    {{-- Boton modal de info del estudiante --}}
                                    @include('admin.comensales.partials.modaldialog') 
                                    
                                    {{-- Boton editar --}}
                                    @include('admin.comensales.partials.modalFormularioEditar') 
                    

                                    {{-- Boton eliminar --}}
                                    @include('admin.comensales.partials.modal')
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>

                            <td colspan="7" class="text-center table-secondary">
                                Total de comensales: {{ $comensales->total() }} | 
                                <a href="{{ route('admin.comensales.index') }}"
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
                    {{ $comensales->appends(['filtro' => $request->filtro])->links() }}
                </div>

            </div>


          
            
        </div>



    </section>
    <script src="{{ asset('assets/js/comensales/editar.js') }}" defer></script>
@endsection
