@extends('layouts.app')

@section('title', 'Sincronizar Data')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div class="row">
        <div class="col-12 mb-4">
            <h2>Sincronizar Data</h2>
            <p class="text-muted">
                Desde aquí puedes sincronizar los datos de estudiantes y empleados con las fuentes externas.
                La barra de progreso muestra el avance de la operación.
            </p>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div id="syncMessage"></div>

                    <div class="d-flex gap-2 mb-4">
                        <button id="startStudentsSync" class="btn btn-primary">Sincronizar estudiantes</button>
                        <button id="startEmployeesSync" class="btn btn-secondary">Sincronizar empleados</button>
                        <button id="refreshSync" class="btn btn-outline-secondary">Actualizar estado</button>
                    </div>

                    <div class="mb-3">
                        <div class="progress">
                            <div id="syncProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;">
                                0%
                            </div>
                        </div>
                        <div class="mt-2 small text-muted" id="syncProgressText">Estado: no iniciado</div>
                    </div>

                    <div class="alert alert-info">
                        <strong>Estado:</strong>
                        <span id="syncStatus">Sincronización no iniciada</span>
                        <br>
                        <small>Proceso actual: <span id="syncCurrentType">Ninguno</span></small>
                    </div>

                    <div class="border rounded p-3 bg-light">
                        <p class="mb-1"><strong>Instrucciones:</strong></p>
                        <ul class="mb-0">
                            <li>Presiona "Sincronizar estudiantes" o "Sincronizar empleados" para ejecutar el proceso en ese orden.</li>
                            <li>La barra de progreso y el estado se actualizan automáticamente.</li>
                            <li>Si ya hay un proceso iniciándose, usa "Actualizar estado".</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const syncProgressBar = document.getElementById('syncProgressBar');
        const syncProgressText = document.getElementById('syncProgressText');
        const syncStatusLabel = document.getElementById('syncStatus');
        const syncCurrentTypeLabel = document.getElementById('syncCurrentType');
        const syncMessage = document.getElementById('syncMessage');
        const startStudentsSyncButton = document.getElementById('startStudentsSync');
        const startEmployeesSyncButton = document.getElementById('startEmployeesSync');
        const refreshSyncButton = document.getElementById('refreshSync');

        const syncStatusUrl = '{{ route("admin.sincronizarData.status") }}';
        const syncActionUrl = '{{ route("admin.sincronizarData") }}';
        const csrfToken = '{{ csrf_token() }}';

        let pollTimer = null;
        let currentType = 'estudiantes';

        const setSyncState = (percent, status, message, type = null) => {
            const value = Math.min(Math.max(percent, 0), 100);
            syncProgressBar.style.width = value + '%';
            syncProgressBar.textContent = value + '%';
            syncProgressText.textContent = message || `${status} (${value}%)`;
            syncStatusLabel.textContent = status;
            syncCurrentTypeLabel.textContent = type ? (type === 'estudiantes' ? 'Estudiantes' : 'Empleados') : 'Ninguno';
        };

        const setAlert = (message, type = 'info') => {
            syncMessage.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        };

        const fetchStatus = async (type = currentType) => {
            try {
                const response = await fetch(`${syncStatusUrl}?type=${type}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('No se pudo obtener el estado de sincronización.');
                }

                const data = await response.json();
                setSyncState(data.percent ?? 0, data.status ?? 'Desconocido', data.message ?? `Progreso ${data.percent ?? 0}%`, type);
            } catch (error) {
                console.error(error);
                setAlert('Error al obtener el estado de sincronización.', 'danger');
            }
        };

        const startPolling = () => {
            if (pollTimer) {
                clearInterval(pollTimer);
            }
            pollTimer = setInterval(() => fetchStatus(currentType), 1200);
            fetchStatus(currentType);
        };

        const stopPolling = () => {
            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }
        };

        const startSync = async (type) => {
            currentType = type;
            startStudentsSyncButton.disabled = true;
            startEmployeesSyncButton.disabled = true;
            refreshSyncButton.disabled = true;
            setAlert('Sincronización iniciada. Espera un momento mientras se procesa.', 'info');
            setSyncState(0, 'Inicializando', 'Solicitando sincronización...', type);
            startPolling();

            try {
                const response = await fetch(syncActionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ type }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.mensaje || 'Error al sincronizar los datos.');
                }

                setSyncState(100, 'Completado', data.mensaje || 'Sincronización completada', type);
                setAlert(data.mensaje || 'Sincronización completada correctamente.', 'success');
            } catch (error) {
                setSyncState(100, 'Error', error.message || 'Error en la sincronización', type);
                setAlert(error.message || 'Ocurrió un error durante la sincronización.', 'danger');
            } finally {
                stopPolling();
                startStudentsSyncButton.disabled = false;
                startEmployeesSyncButton.disabled = false;
                refreshSyncButton.disabled = false;
                fetchStatus(currentType);
            }
        };

        startStudentsSyncButton.addEventListener('click', () => startSync('estudiantes'));
        startEmployeesSyncButton.addEventListener('click', () => startSync('empleados'));
        refreshSyncButton.addEventListener('click', () => fetchStatus(currentType));

        fetchStatus(currentType);
    });
</script>
@endsection
