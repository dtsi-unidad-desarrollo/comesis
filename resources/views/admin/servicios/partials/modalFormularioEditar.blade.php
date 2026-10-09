{{-- Boton de editar servicio --}}
<button type="button" class="btn btn-sm btn-outline-warning rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
    style="width: 32px; height: 32px;"
    data-bs-toggle="modal"
    data-bs-target="#modalformularioEditarServicio{{ $servicio->id }}"
    title="Editar servicio">
    <i class="bi bi-pencil"></i>
</button>

<!-- Modal formulario editar servicio -->
<div class="modal fade text-start" id="modalformularioEditarServicio{{ $servicio->id }}" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel{{ $servicio->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="staticBackdropLabel{{ $servicio->id }}">
                    <i class="bi bi-pencil-square me-2"></i>Actualizar Servicio: {{ $servicio->nombre }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.servicios.update', $servicio->id) }}" method="POST"
                    id="formularioEditarServicio{{ $servicio->id }}" class="row g-3 needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Nombre del servicio -->
                    <div class="col-12">
                        <label for="nombreEditar{{ $servicio->id }}" class="form-label fw-semibold text-secondary small mb-1">Nombre del Servicio</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary-subtle">
                                <i class="bi bi-cup-hot text-primary"></i>
                            </span>
                            <input type="text" name="nombre" class="form-control border-secondary-subtle" id="nombreEditar{{ $servicio->id }}"
                                placeholder="Ingrese nombre del servicio"
                                value="{{ old('nombre') ?? $servicio->nombre }}" required>
                        </div>
                        @error('nombre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora inicio -->
                    <div class="col-md-6">
                        <label for="hora_inicio_edit{{ $servicio->id }}" class="form-label fw-semibold text-secondary small mb-1">Hora Apertura</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary-subtle">
                                <i class="bi bi-clock text-primary"></i>
                            </span>
                            <input type="time" name="hora_inicio" class="form-control border-secondary-subtle" id="hora_inicio_edit{{ $servicio->id }}"
                                value="{{ old('hora_inicio') ?? $servicio->hora_inicio }}" required>
                        </div>
                        @error('hora_inicio')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora cierre -->
                    <div class="col-md-6">
                        <label for="hora_cierre_edit{{ $servicio->id }}" class="form-label fw-semibold text-secondary small mb-1">Hora Cierre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary-subtle">
                                <i class="bi bi-clock-history text-primary"></i>
                            </span>
                            <input type="time" name="hora_cierre" class="form-control border-secondary-subtle" id="hora_cierre_edit{{ $servicio->id }}"
                                value="{{ old('hora_cierre') ?? $servicio->hora_cierre }}" required>
                        </div>
                        @error('hora_cierre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Disponibilidad -->
                    <div class="col-md-6">
                        <label for="disponibilidad_edit{{ $servicio->id }}" class="form-label fw-semibold text-secondary small mb-1">Cupos / Bandejas Diarias</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-secondary-subtle">
                                <i class="bi bi-box-seam text-primary"></i>
                            </span>
                            <input type="number" name="disponibilidad" class="form-control border-secondary-subtle"
                                id="disponibilidad_edit{{ $servicio->id }}" min="0" max="10000"
                                value="{{ old('disponibilidad') ?? $servicio->disponibilidad }}" required>
                        </div>
                        @error('disponibilidad')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estatus Switch -->
                    <div class="col-md-6 d-flex align-items-center pt-3">
                        <div class="form-check form-switch ps-5">
                            <input class="form-check-input" type="checkbox" role="switch"
                                id="switchEstatus{{ $servicio->id }}" name="estatus" value="1"
                                {{ $servicio->estatus ? 'checked' : '' }} style="transform: scale(1.4);">
                            <label class="form-check-label ms-2 fw-semibold text-dark" for="switchEstatus{{ $servicio->id }}">
                                Servicio {{ $servicio->estatus ? 'Activo' : 'Inactivo' }}
                            </label>
                        </div>
                    </div>

                    <div class="col-12 pt-2 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" type="submit">
                            <i class="bi bi-check-circle-fill me-1"></i> Actualizar Servicio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
