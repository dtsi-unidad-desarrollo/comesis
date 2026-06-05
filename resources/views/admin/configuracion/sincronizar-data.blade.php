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
                        <button id="startStudentsSync" class="btn btn-primary">Preparar sincronización estudiantes</button>
                        <button id="startEmployeesSync" class="btn btn-secondary">Preparar sincronización empleados</button>
                        <button id="refreshSync" class="btn btn-outline-secondary">Actualizar estado</button>
                    </div>

                    <div id="studentSyncSteps" class="d-none mb-3">
                        <div class="small text-muted mb-2">Sincronización de estudiantes en 3 pasos. Total registros: <span id="studentTotal">0</span>.</div>
                        <div class="d-flex gap-2 mb-2">
                            <button id="studentStep1" class="btn btn-outline-primary">Estudiantes 1/3</button>
                            <button id="studentStep2" class="btn btn-outline-primary">Estudiantes 2/3</button>
                            <button id="studentStep3" class="btn btn-outline-primary">Estudiantes 3/3</button>
                        </div>
                    </div>

                    <div id="employeeSyncSteps" class="d-none mb-3">
                        <div class="small text-muted mb-2">Sincronización de empleados en 3 pasos. Total registros: <span id="employeeTotal">0</span>.</div>
                        <div class="d-flex gap-2 mb-2">
                            <button id="employeeStep1" class="btn btn-outline-secondary">Empleados 1/3</button>
                            <button id="employeeStep2" class="btn btn-outline-secondary">Empleados 2/3</button>
                            <button id="employeeStep3" class="btn btn-outline-secondary">Empleados 3/3</button>
                        </div>
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
        const startStudentStep1 = document.getElementById('studentStep1');
        const startStudentStep2 = document.getElementById('studentStep2');
        const startStudentStep3 = document.getElementById('studentStep3');
        const startEmployeeStep1 = document.getElementById('employeeStep1');
        const startEmployeeStep2 = document.getElementById('employeeStep2');
        const startEmployeeStep3 = document.getElementById('employeeStep3');
        const studentSyncSteps = document.getElementById('studentSyncSteps');
        const employeeSyncSteps = document.getElementById('employeeSyncSteps');
        const studentTotalLabel = document.getElementById('studentTotal');
        const employeeTotalLabel = document.getElementById('employeeTotal');
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

        const parseJsonResponse = async (response) => {
            const text = await response.text();
            try {
                return JSON.parse(text);
            } catch (error) {
                throw new Error(`Respuesta inválida del servidor: ${text}`);
            }
        };

        const toggleSyncSteps = (type) => {
            studentSyncSteps.classList.toggle('d-none', type !== 'estudiantes');
            employeeSyncSteps.classList.toggle('d-none', type !== 'empleados');
        };

        const enableStepButtons = (buttons, enable) => {
            buttons.forEach(button => button.disabled = !enable);
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
                    const text = await response.text();
                    throw new Error(`Error de estado: ${response.status} ${text}`);
                }

                const data = await parseJsonResponse(response);
                setSyncState(data.percent ?? 0, data.status ?? 'Desconocido', data.message ?? `Progreso ${data.percent ?? 0}%`, type);
            } catch (error) {
                console.error(error);
                setAlert(error.message || 'Error al obtener el estado de sincronización.', 'danger');
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

        const prepareSync = async (type) => {
            currentType = type;
            startStudentsSyncButton.disabled = true;
            startEmployeesSyncButton.disabled = true;
            refreshSyncButton.disabled = true;
            setAlert('Preparando sincronización. Calculando registros...', 'info');
            setSyncState(0, 'Preparando', 'Calculando registros...', type);
            toggleSyncSteps(type);

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

                const data = await parseJsonResponse(response);
                if (!response.ok) {
                    throw new Error(data.mensaje || `Error al preparar sincronización: ${response.status}`);
                }

                if (type === 'estudiantes') {
                    studentTotalLabel.textContent = data.total ?? 0;
                    enableStepButtons([startStudentStep1, startStudentStep2, startStudentStep3], true);
                } else {
                    employeeTotalLabel.textContent = data.total ?? 0;
                    enableStepButtons([startEmployeeStep1, startEmployeeStep2, startEmployeeStep3], true);
                }

                setAlert(data.mensaje || 'Sincronización preparada. Usa los botones de paso.', 'success');
                fetchStatus(type);
            } catch (error) {
                setSyncState(0, 'Error', error.message || 'Error al preparar sincronización', type);
                setAlert(error.message || 'Ocurrió un error durante la preparación.', 'danger');
                toggleSyncSteps(null);
            } finally {
                startStudentsSyncButton.disabled = false;
                startEmployeesSyncButton.disabled = false;
                refreshSyncButton.disabled = false;
            }
        };

        const executeSyncStep = async (type, step) => {
            currentType = type;
            const stepButtons = type === 'estudiantes'
                ? [startStudentStep1, startStudentStep2, startStudentStep3]
                : [startEmployeeStep1, startEmployeeStep2, startEmployeeStep3];
            startStudentsSyncButton.disabled = true;
            startEmployeesSyncButton.disabled = true;
            enableStepButtons(stepButtons, false);
            refreshSyncButton.disabled = true;
            setAlert(`Ejecutando paso ${step} de sincronización ${type === 'estudiantes' ? 'estudiantes' : 'empleados'}...`, 'info');
            setSyncState(0, 'Ejecutando', `Ejecutando paso ${step}...`, type);
            startPolling();

            try {
                const response = await fetch(syncActionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ type, step }),
                });

                const data = await parseJsonResponse(response);
                if (!response.ok) {
                    throw new Error(data.mensaje || `Error al ejecutar paso ${step}: ${response.status}`);
                }

                setAlert(data.mensaje || `Paso ${step} completado correctamente.`, 'success');
                fetchStatus(type);
            } catch (error) {
                setSyncState(100, 'Error', error.message || 'Error en la sincronización', type);
                setAlert(error.message || 'Ocurrió un error durante la sincronización.', 'danger');
            } finally {
                stopPolling();
                startStudentsSyncButton.disabled = false;
                startEmployeesSyncButton.disabled = false;
                refreshSyncButton.disabled = false;
                enableStepButtons(stepButtons, true);
            }
        };

        startStudentsSyncButton.addEventListener('click', () => prepareSync('estudiantes'));
        startEmployeesSyncButton.addEventListener('click', () => prepareSync('empleados'));
        startStudentStep1.addEventListener('click', () => executeSyncStep('estudiantes', 1));
        startStudentStep2.addEventListener('click', () => executeSyncStep('estudiantes', 2));
        startStudentStep3.addEventListener('click', () => executeSyncStep('estudiantes', 3));
        startEmployeeStep1.addEventListener('click', () => executeSyncStep('empleados', 1));
        startEmployeeStep2.addEventListener('click', () => executeSyncStep('empleados', 2));
        startEmployeeStep3.addEventListener('click', () => executeSyncStep('empleados', 3));
        refreshSyncButton.addEventListener('click', () => fetchStatus(currentType));

        fetchStatus(currentType);
    });
</script>
@endsection
