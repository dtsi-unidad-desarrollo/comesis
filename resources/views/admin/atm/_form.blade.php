@csrf

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary small mb-1">Nombre de la Taquilla</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-secondary-subtle">
                <i class="bi bi-cpu text-primary"></i>
            </span>
            <input type="text" name="nombre" value="{{ old('nombre', $atm->nombre ?? '') }}" 
                   class="form-control border-secondary-subtle" placeholder="Ej: Taquilla 1 - Puerta Principal" required>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary small mb-1">Torniquete Físico Vinculado</label>
        <select name="torniquete_id" class="form-select border-secondary-subtle" required>
            <option value="">-- Seleccionar Torniquete --</option>
            @foreach($torniquetes as $t)
                <option value="{{ $t->id }}" @if((isset($atm) && $atm->torniquete_id == $t->id) || old('torniquete_id') == $t->id) selected @endif>
                    {{ $t->nombre }} ({{ $t->tipo ?? 'Torniquete' }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold text-secondary small mb-1">Descripción / Notas</label>
        <textarea name="descripcion" class="form-control border-secondary-subtle" rows="3" 
                  placeholder="Detalles sobre la ubicación o equipo...">{{ old('descripcion', $atm->descripcion ?? '') }}</textarea>
    </div>
</div>

<div class="pt-4 border-top mt-4 d-flex justify-content-end gap-2">
    <a href="{{ route('admin.atms.index') }}" class="btn btn-light rounded-pill px-4">Cancelar</a>
    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
        <i class="bi bi-check-circle-fill me-1"></i> Guardar Taquilla
    </button>
</div>
