@extends('layouts.app')

@section('title', 'Nuevo Apoyo Institucional')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Registrar Apoyo Institucional</h1>
        </div>
    </div>

    @include('partials.alert')

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.apoyos.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="institucion" class="form-label">Institución Externa</label>
                        <input type="text" class="form-control @error('institucion') is-invalid @enderror" id="institucion" name="institucion" value="{{ old('institucion') }}" required placeholder="Ej. Ministerio de Educación">
                        @error('institucion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="codigo" class="form-label">Código (El que se escaneará en recepción)</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo') }}" required placeholder="Ej. APOYO-1234">
                            <button class="btn btn-outline-secondary" type="button" onclick="generateCode()" title="Generar Código Aleatorio">
                                <i class="bi bi-arrow-clockwise"></i> Generar
                            </button>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cantidad_habilitada" class="form-label">Cantidad de Comidas Habilitadas</label>
                        <input type="number" class="form-control @error('cantidad_habilitada') is-invalid @enderror" id="cantidad_habilitada" name="cantidad_habilitada" value="{{ old('cantidad_habilitada', 1) }}" required min="1">
                        @error('cantidad_habilitada')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="estatus" class="form-label">Estatus</label>
                        <select class="form-select @error('estatus') is-invalid @enderror" id="estatus" name="estatus" required>
                            <option value="1" {{ old('estatus') == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estatus') == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estatus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                    <a href="{{ route('admin.apoyos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = 'APOYO-';
    for (let i = 0; i < 6; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('codigo').value = result;
}
</script>
@endsection
