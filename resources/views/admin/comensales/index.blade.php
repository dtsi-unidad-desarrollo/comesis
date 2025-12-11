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

                {{-- Formulario de importación masiva desde Excel --}}
                <div class="mt-3 d-flex gap-2 align-items-center">
                    <a href="{{ route('admin.comensales.template.xlsx') }}" class="btn btn-outline-secondary">Descargar plantilla (.xlsx)</a>
                    <a href="{{ route('admin.comensales.export') }}" class="btn btn-outline-primary">Exportar registrados (.xlsx)</a>
                    <form action="{{ route('admin.comensales.import') }}" method="post" enctype="multipart/form-data" class="m-0">
                        @csrf
                        <div class="input-group">
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <button class="btn btn-success" type="submit">Importar Excel</button>
                        </div>
                    </form>
                </div>

                @if(session('import_errores'))
                    <div class="alert alert-warning mt-2">
                        <strong>Errores en la importación:</strong>
                        <ul class="mb-0">
                            @foreach(session('import_errores') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                                <td id="estatus-{{ $comensal->id }}" class="{{ $comensal->estatus ? 'table-success' : 'table-danger'}}">
    <div class="form-check form-switch d-flex align-items-center">
        <input
            class="form-check-input estatus-switch"
            type="checkbox"
            id="switch-{{ $comensal->id }}"
            data-id="{{ $comensal->id }}"
            {{ $comensal->estatus ? 'checked' : '' }}
        >
        <label class="form-check-label ms-2" for="switch-{{ $comensal->id }}">
            {{ $comensal->estatus ? 'ACTIVO' : 'INACTIVO' }}
        </label>
    </div>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const switches = document.querySelectorAll('.estatus-switch');
    switches.forEach(function (cb) {
        cb.addEventListener('change', function () {
            const id = this.dataset.id;
            const checked = this.checked;
            const url = `/comensales/${id}/toggle`;
            const token = '{{ csrf_token() }}';
            const switchEl = this;
            const container = document.getElementById('estatus-'+id);
            const label = container ? container.querySelector('.form-check-label') : null;

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (!ok || data.error) {
                    alert(data.error || 'Error al actualizar el estatus.');
                    switchEl.checked = !checked;
                    return;
                }
                const nuevoEstatus = Number(data.estatus);
                if (container) {
                    container.classList.toggle('table-success', nuevoEstatus === 1);
                    container.classList.toggle('table-danger', nuevoEstatus !== 1);
                }
                if (label) {
                    label.textContent = nuevoEstatus === 1 ? 'ACTIVO' : 'INACTIVO';
                }
            })
            .catch(err => {
                alert('Error de red al actualizar estatus.');
                switchEl.checked = !checked;
            });
        });
    });
});
</script>
    @endsection

