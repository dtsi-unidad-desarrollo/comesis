<!-- Botón de Eliminar Entrada -->
<button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-0 d-inline-flex align-items-center justify-content-center"
        style="width: 32px; height: 32px;"
        data-bs-toggle="modal" data-bs-target="#verticalycentered{{ $entrada->id }}"
        title="Eliminar registro">
    <i class="bi bi-trash"></i>
</button>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="verticalycentered{{ $entrada->id }}" tabindex="-1" aria-labelledby="modalEliminarLabel{{ $entrada->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white py-3 border-0">
                <h5 class="modal-title fw-bold" id="modalEliminarLabel{{ $entrada->id }}">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-start">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                        <i class="bi bi-trash-fill fs-2"></i>
                    </div>
                </div>
                <p class="text-muted text-center mb-3">
                    ¿Estás seguro de que deseas eliminar la entrada de:
                </p>
                <div class="p-3 bg-light rounded-3 text-center mb-3 border">
                    <h5 class="fw-bold text-dark mb-1">{{ $entrada->nombres }} {{ $entrada->apellidos }}</h5>
                    <span class="badge bg-secondary font-monospace">{{ $entrada->nacionalidad }}-{{ $entrada->cedula }}</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1">{{ $entrada->tipo_comensal }}</span>
                    <div class="small text-muted mt-2">
                        <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($entrada->fecha)->format('d/m/Y') }} a las {{ $entrada->hora }} ({{ $entrada->comida }})
                    </div>
                </div>
                <div class="alert alert-warning py-2 px-3 small border-0 mb-0 d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2 fs-5 flex-shrink-0"></i>
                    <div>Al eliminar esta entrada, el comensal quedará habilitado para ingresar nuevamente en este servicio. Procede solo si hubo un error en la taquilla.</div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-3">
                <form action="{{ route('admin.entradas.destroy', $entrada->id) }}" method="POST" class="w-100 d-flex gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary w-50 rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger w-50 rounded-pill shadow-sm">
                        <i class="bi bi-trash me-1"></i> Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
