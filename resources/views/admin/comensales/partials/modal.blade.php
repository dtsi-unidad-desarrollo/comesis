
        
<!-- Vertically centered Modal -->
<a type="button" class="text-danger" data-bs-toggle="modal" data-bs-target="#verticalycentered{{ $comensal->id }}">
    <i class="bi bi-trash fs-4"></i>
</a>

<div class="modal fade" id="verticalycentered{{ $comensal->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Eliminando</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ¿Esta seguro que desea desactivar el comensal <span class="text-danger fs-5">{{$comensal->nombre}}</span>? 
            Si procede, ¡no podrá acceder al comedor!.
        </div>
        <div class="modal-footer">
            <form action="{{ route('admin.comensales.destroy', $comensal->id) }}" method="post" >
            @csrf
            @method('delete')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger">Si, proceder a desactivar.</button>
            </form>
        </div>
    </div>
    </div>
</div><!-- End Vertically centered Modal-->


