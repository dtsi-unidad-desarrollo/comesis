<div class="modal fade text-start" id="modalCrearRol" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCrearRolLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="modalCrearRolLabel">
                    <i class="bi bi-shield-plus me-2"></i>Crear Nuevo Rol
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-secondary small mb-1">Nombre del Rol</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control border-secondary-subtle" 
                                   placeholder="Ej: SUPERVISOR, REPORTES..." required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary small mb-1">Estatus</label>
                            <select name="estatus" class="form-select border-secondary-subtle">
                                <option value="1" selected>ACTIVO</option>
                                <option value="0">INACTIVO</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <label class="form-label fw-semibold text-secondary small mb-2">Permisos Asociados</label>
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="row g-2">
                                    @foreach($permisos as $permiso)
                                        <div class="col-md-6">
                                            <div class="form-check p-2 bg-white rounded border">
                                                <input class="form-check-input ms-0 me-2" type="checkbox" name="permisos[]" 
                                                       value="{{ $permiso->id }}" id="permiso-create-{{ $permiso->id }}" 
                                                       {{ in_array($permiso->id, old('permisos', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium text-dark small" for="permiso-create-{{ $permiso->id }}">
                                                    {{ $permiso->nombre }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-top mt-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                            <i class="bi bi-check-circle-fill me-1"></i> Guardar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
