<!-- Modal Sincronizar Data -->
<a type="button" class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#sincronizardata">
    <i class="bi bi-arrow-repeat me-1"></i> Sincronizar con DUX / Terepaima
</a>

<div class="modal fade" id="sincronizardata" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-arrow-repeat text-primary me-1"></i> Sincronización de Comensales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.sincronizarData') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Seleccione el origen de datos que desea sincronizar para actualizar el padrón local de comensales en COMESIS:
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Tipo de Sincronización:</label>
                        <select name="tipo" class="form-select">
                            <option value="todos" selected>⚡ Sincronizar Todo (Estudiantes DUX y Empleados TEREPAIMA)</option>
                            <option value="estudiantes">🎓 Solo Estudiantes (DUX - SISE/ARSE)</option>
                            <option value="empleados">💼 Solo Empleados y Personal (TEREPAIMA - RRHH)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="bi bi-cloud-arrow-down me-1"></i> Iniciar Sincronización
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>