@extends('layouts.app')

@section('title', 'Editar Apoyo Institucional')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Editar Apoyo Institucional</h1>
        </div>
    </div>

    @include('partials.alert')

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.apoyos.update', $apoyo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="institucion" class="form-label">Institución Externa</label>
                        <input type="text" class="form-control @error('institucion') is-invalid @enderror" id="institucion" name="institucion" value="{{ old('institucion', $apoyo->institucion) }}" required>
                        @error('institucion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo', $apoyo->codigo) }}" required>
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cantidad_habilitada" class="form-label">Cantidad de Comidas Habilitadas</label>
                        <input type="number" class="form-control @error('cantidad_habilitada') is-invalid @enderror" id="cantidad_habilitada" name="cantidad_habilitada" value="{{ old('cantidad_habilitada', $apoyo->cantidad_habilitada) }}" required min="1">
                        @error('cantidad_habilitada')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="estatus" class="form-label">Estatus</label>
                        <select class="form-select @error('estatus') is-invalid @enderror" id="estatus" name="estatus" required>
                            <option value="1" {{ old('estatus', $apoyo->estatus) == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estatus', $apoyo->estatus) == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estatus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                            <strong>Nota:</strong> Hasta el momento, este código ha consumido <strong>{{ $apoyo->cantidad_consumida }}</strong> comidas. 
                            Si reduces las comidas habilitadas por debajo de esta cantidad, ya no podrán registrar más entradas hasta que lo aumentes.
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.apoyos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
