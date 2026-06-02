        
<!-- Vertically centered Modal -->
<a type="button" class="mb-3" data-bs-toggle="modal" data-bs-target="#sincronizardata">
    <i class="bi bi-db text-primary"></i> Sincronizar data con dux
</a>

<div class="modal fade" id="sincronizardata" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Data Dux</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ¿Desea cincronizar los datos de comensales con dux, esto traera la información de los estudiantes y actualizara la data del sistema COMESIS? 
        </div>
        <div class="modal-footer">
            <form action="{{ route('admin.sincronizarData') }}" method="post" >
            @csrf
            @method('post')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Si</button>
            </form>
        </div>
    </div>
    </div>
</div><!-- End Vertically centered Modal-->