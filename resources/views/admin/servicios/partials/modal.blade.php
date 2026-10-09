<!-- Vertically centered Modal Eliminar/Desactivar -->
<button type="button" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center"
    style="width: 32px; height: 32px;"
    data-bs-toggle="modal"
    data-bs-target="#modalEliminarServicio{{ $servicio->id }}"
    title="Desactivar o eliminar servicio">
    <i class="bi bi-trash"></i>
</button>

<div class="modal fade" id="modalEliminarServicio{{ $servicio->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow text-start">
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Desactivar Servicio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                ¿Está seguro que desea desactivar el servicio <strong class="text-dark">{{ $servicio->nombre }}</strong>?
                <p class="small text-danger mt-2 mb-0">
                    <i class="bi bi-info-circle me-1"></i> Al desactivarlo, los comensales no podrán registrar entradas para este servicio en los torniquetes o taquillas.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.servicios.destroy', $servicio->id) }}" method="post" class="m-0">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">
                        Sí, Proceder a Desactivar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
