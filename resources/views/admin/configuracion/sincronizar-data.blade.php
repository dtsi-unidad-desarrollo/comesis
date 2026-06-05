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
                        <button id="startSync" class="btn btn-primary">Iniciar sincronización</button>
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
                    </div>

                    <div class="border rounded p-3 bg-light">
                        <p class="mb-1"><strong>Instrucciones:</strong></p>
                        <ul class="mb-0">
                            <li>Presiona "Iniciar sincronización" para traer y actualizar los datos.</li>
                            <li>El progreso se actualiza automáticamente durante el proceso.</li>
                            <li>Si la sincronización ya está en curso, presiona "Actualizar estado".</li>
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
        const syncMessage = document.getElementById('syncMessage');
        const startSyncButton = document.getElementById('startSync');
        const refreshSyncButton = document.getElementById('refreshSync');

        const syncStatusUrl = '{{ route("admin.sincronizarData.status") }}';
        const syncActionUrl = '{{ route("admin.sincronizarData") }}';
        const csrfToken = '{{ csrf_token() }}';

        let pollTimer = null;

        const setSyncState = (percent, status, message) => {
            const value = Math.min(Math.max(percent, 0), 100);
            syncProgressBar.style.width = value + '%';
            syncProgressBar.textContent = value + '%';
            syncProgressText.textContent = message || `${status} (${value}%)`;
            syncStatusLabel.textContent = status;
        };

        const setAlert = (message, type = 'info') => {
            syncMessage.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        };

        const fetchStatus = async () => {
            try {
                const response = await fetch(syncStatusUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('No se pudo obtener el estado de sincronización.');
                }

                const data = await response.json();
                setSyncState(data.percent ?? 0, data.status ?? 'Desconocido', data.message ?? `Progreso ${data.percent ?? 0}%`);
            } catch (error) {
                console.error(error);
            }
        };

        const startPolling = () => {
            if (pollTimer) {
                clearInterval(pollTimer);
            }
            pollTimer = setInterval(fetchStatus, 1200);
            fetchStatus();
        };

        const stopPolling = () => {
            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }
        };

        refreshSyncButton.addEventListener('click', fetchStatus);

        startSyncButton.addEventListener('click', async () => {
            startSyncButton.disabled = true;
            refreshSyncButton.disabled = true;
            setAlert('Sincronización iniciada. Espera un momento mientras se procesa.', 'info');
            setSyncState(0, 'Inicializando', 'Solicitando sincronización...');
            startPolling();

            try {
                const response = await fetch(syncActionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({}),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.mensaje || 'Error al sincronizar los datos.');
                }

                setSyncState(100, 'Completado', data.mensaje || 'Sincronización completada');
                setAlert(data.mensaje || 'Sincronización completada correctamente.', 'success');
            } catch (error) {
                setSyncState(100, 'Error', error.message || 'Error en la sincronización');
                setAlert(error.message || 'Ocurrió un error durante la sincronización.', 'danger');
            } finally {
                stopPolling();
                startSyncButton.disabled = false;
                refreshSyncButton.disabled = false;
                fetchStatus();
            }
        });

        fetchStatus();
    });
</script>
@endsection
