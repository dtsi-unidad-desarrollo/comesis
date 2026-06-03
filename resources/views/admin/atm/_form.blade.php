@csrf

<div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', $atm->nombre ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">MAC address</label>
    <div class="input-group">
        <input type="text" name="mac_address" value="{{ old('mac_address', $atm->mac_address ?? $mac) }}" class="form-control">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">IP address</label>
    <div class="input-group">
        <input type="text" name="ip_address" value="{{ old('ip_address', $atm->ip_address ?? $ip) }}" class="form-control">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Torniquete</label>
    <select name="torniquete_id" class="form-select" required>
        <option value="">-- Ninguno --</option>
        @foreach($torniquetes as $t)
            <option value="{{ $t->id }}" @if((isset($atm) && $atm->torniquete_id == $t->id) || old('torniquete_id') == $t->id) selected @endif>{{ $t->nombre }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control">{{ old('descripcion', $atm->descripcion ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">Guardar</button>
<a href="{{ route('admin.atms.index') }}" class="btn btn-secondary">Cancelar</a>


