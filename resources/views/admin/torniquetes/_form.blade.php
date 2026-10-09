@csrf

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary small mb-1">Nombre del Dispositivo / Torniquete</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-secondary-subtle">
                <i class="bi bi-door-open text-primary"></i>
            </span>
            <input type="text" name="nombre" value="{{ old('nombre', $torniquete->nombre ?? '') }}" 
                   class="form-control border-secondary-subtle" placeholder="Ej: Torniquete Entrada 1" required>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary small mb-1">Tipo o Ubicación</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-secondary-subtle">
                <i class="bi bi-geo-alt text-primary"></i>
            </span>
            <input type="text" name="tipo" value="{{ old('tipo', $torniquete->tipo ?? '') }}" 
                   class="form-control border-secondary-subtle" placeholder="Ej: Entrada Principal, Puerta 2">
        </div>
    </div>

    <div class="col-md-8">
        <label class="form-label fw-semibold text-secondary small mb-1">Endpoint URL (API / Relé de Apertura)</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-secondary-subtle">
                <i class="bi bi-link-45deg text-primary"></i>
            </span>
            <input type="url" name="endpoint_url" value="{{ old('endpoint_url', $torniquete->endpoint_url ?? '') }}" 
                   class="form-control border-secondary-subtle font-monospace" placeholder="http://192.168.1.50/api/open">
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold text-secondary small mb-1">Estatus</label>
        <select name="estatus" class="form-select border-secondary-subtle">
            <option value="1" {{ old('estatus', $torniquete->estatus ?? '1') == '1' ? 'selected' : '' }}>ACTIVO</option>
            <option value="0" {{ old('estatus', $torniquete->estatus ?? '') == '0' ? 'selected' : '' }}>INACTIVO</option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold text-secondary small mb-1">Descripción / Observaciones</label>
        <textarea name="descripcion" class="form-control border-secondary-subtle" rows="3" 
                  placeholder="Detalles técnicos o notas sobre el dispositivo...">{{ old('descripcion', $torniquete->descripcion ?? '') }}</textarea>
    </div>
</div>

<div class="pt-4 border-top mt-4 d-flex justify-content-end gap-2">
    <a href="{{ route('admin.torniquetes.index') }}" class="btn btn-light rounded-pill px-4">Cancelar</a>
    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
        <i class="bi bi-check-circle-fill me-1"></i> Guardar Torniquete
    </button>
</div>
