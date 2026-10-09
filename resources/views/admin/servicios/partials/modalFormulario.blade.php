{{-- Boton de nuevo servicio --}}
<button type="button" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#ModalServiciosNuevo">
    <i class="bi bi-plus-circle-fill me-2 fs-5"></i>
    <span>Nuevo Servicio</span>
</button>

<!-- Modal formulario crear servicio -->
<div class="modal fade text-start" id="ModalServiciosNuevo" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="staticBackdropLabel">
                    <i class="bi bi-cup-hot-fill me-2"></i>Registrar Nuevo Servicio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.servicios.store') }}" method="POST" id="formularioCrearServicio"
                    class="row g-3 needs-validation" novalidate>
                    @csrf

                    <!-- Nombre del servicio -->
                    <div class="col-12">
                        <label for="nombreServicio" class="form-label fw-semibold text-secondary small mb-1">Nombre del Servicio</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary-subtle">
                                <i class="bi bi-cup-hot text-primary"></i>
                            </span>
                            <input type="text" name="nombre" class="form-control border-secondary-subtle" id="nombreServicio"
                                placeholder="Ej: ALMUERZO, CENA, DESAYUNO..."
                                value="{{ old('nombre') }}" required>
                        </div>
                        @error('nombre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora inicio -->
                    <div class="col-md-6">
                        <label for="hora_inicio" class="form-label fw-semibold text-secondary small mb-1">Hora de Apertura</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary-subtle">
                                <i class="bi bi-clock text-primary"></i>
                            </span>
                            <input type="time" name="hora_inicio" class="form-control border-secondary-subtle" id="hora_inicio"
                                value="{{ old('hora_inicio') }}" required>
                        </div>
                        @error('hora_inicio')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora cierre -->
                    <div class="col-md-6">
                        <label for="hora_cierre" class="form-label fw-semibold text-secondary small mb-1">Hora de Cierre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary-subtle">
                                <i class="bi bi-clock-history text-primary"></i>
                            </span>
                            <input type="time" name="hora_cierre" class="form-control border-secondary-subtle" id="hora_cierre"
                                value="{{ old('hora_cierre') }}" required>
                        </div>
                        @error('hora_cierre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Disponibilidad -->
                    <div class="col-12">
                        <label for="disponibilidad" class="form-label fw-semibold text-secondary small mb-1">Cupos / Bandejas Diarias Habilitadas</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary-subtle">
                                <i class="bi bi-box-seam text-primary"></i>
                            </span>
                            <input type="number" name="disponibilidad" class="form-control border-secondary-subtle" 
                                min="0" max="10000" step="1" id="disponibilidad"
                                placeholder="Ej: 500 bandejas"
                                value="{{ old('disponibilidad', 500) }}" required>
                        </div>
                        @error('disponibilidad')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 pt-2 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" type="submit">
                            <i class="bi bi-check-circle-fill me-1"></i> Guardar Servicio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
