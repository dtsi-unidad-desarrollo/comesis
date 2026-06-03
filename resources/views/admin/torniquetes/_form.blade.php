@csrf

<div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', $torniquete->nombre ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Endpoint URL</label>
    <input type="url" name="endpoint_url" value="{{ old('endpoint_url', $torniquete->endpoint_url ?? '') }}" class="form-control" placeholder="https://example.com/api/turnstile">
</div>

<div class="mb-3">
    <label class="form-label">Tipo</label>
    <input type="text" name="tipo" value="{{ old('tipo', $torniquete->tipo ?? '') }}" class="form-control" placeholder="Ej. Principal, Secundario">
</div>

<div class="mb-3">
    <label class="form-label">Estatus</label>
    <select name="estatus" class="form-select">
        <option value="1" {{ old('estatus', $torniquete->estatus ?? '1') == '1' ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('estatus', $torniquete->estatus ?? '') == '0' ? 'selected' : '' }}>Inactivo</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion', $torniquete->descripcion ?? '') }}</textarea>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.torniquetes.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
