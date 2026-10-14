<!-- Modal de Aviso de Inactividad -->
<div class="modal fade" id="modalInactividad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalInactividadLabel" aria-hidden="true" style="z-index: 1090;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-warning text-dark py-3 px-4">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="modalInactividadLabel">
                    <i class="bi bi-clock-history fs-4 me-2"></i> Aviso de Inactividad
                </h5>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center p-3 mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                </div>
                <h5 class="fw-bold mb-2 text-dark">Tu sesión está a punto de expirar</h5>
                <p class="text-muted small mb-3">
                    No hemos detectado interacción en los últimos minutos. Por seguridad y para liberar recursos y taquillas asignadas, la sesión se cerrará automáticamente en:
                </p>
                <div class="mb-4">
                    <span id="idleCountdownDisplay" class="badge bg-danger rounded-pill px-4 py-2 fs-5 font-monospace shadow-sm">
                        60s
                    </span>
                </div>
                <p class="small text-muted mb-0">¿Deseas continuar trabajando en el sistema?</p>
            </div>
            <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3 fw-semibold" id="btnCerrarSesionInactividad">
                    <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" id="btnMantenerSesionActiva">
                    <i class="bi bi-arrow-repeat me-1"></i> Mantener Sesión Activa
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Interceptor global para error 419 (CSRF / Sesión expirada)
    if (window.fetch) {
        const originalFetch = window.fetch;
        window.fetch = function () {
            return originalFetch.apply(this, arguments).then(function (response) {
                if (response.status === 419) {
                    window.location.href = "{{ route('login.index') }}?expired=1";
                }
                return response;
            });
        };
    }

    // Configuración de tiempos de inactividad
    const IDLE_TIME_MS = 15 * 60 * 1000; // 15 minutos de inactividad
    const COUNTDOWN_SECONDS = 60;        // 60 segundos de aviso en modal

    let idleTimer = null;
    let countdownInterval = null;
    let secondsLeft = COUNTDOWN_SECONDS;
    const modalEl = document.getElementById('modalInactividad');
    if (!modalEl || typeof bootstrap === 'undefined') return;

    const bsModal = new bootstrap.Modal(modalEl);
    const countdownDisplay = document.getElementById('idleCountdownDisplay');
    const btnMantener = document.getElementById('btnMantenerSesionActiva');
    const btnCerrar = document.getElementById('btnCerrarSesionInactividad');

    function resetIdleTimer() {
        if (modalEl.classList.contains('show')) return; // No reiniciar si el modal ya se está mostrando

        clearTimeout(idleTimer);
        idleTimer = setTimeout(triggerIdleWarning, IDLE_TIME_MS);
    }

    function triggerIdleWarning() {
        secondsLeft = COUNTDOWN_SECONDS;
        countdownDisplay.textContent = secondsLeft + 's';
        bsModal.show();

        clearInterval(countdownInterval);
        countdownInterval = setInterval(function () {
            secondsLeft--;
            countdownDisplay.textContent = secondsLeft + 's';

            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                ejecutarCierrePorInactividad();
            }
        }, 1000);
    }

    function ejecutarCierrePorInactividad() {
        const logoutForm = document.querySelector('form[action="{{ route('logout') }}"]');
        if (logoutForm) {
            logoutForm.submit();
        } else {
            window.location.href = "{{ route('logout.get') }}";
        }
    }

    // Mantener sesión activa (Keep-alive)
    if (btnMantener) {
        btnMantener.addEventListener('click', function () {
            btnMantener.disabled = true;
            btnMantener.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Renovando...';

            fetch("{{ route('session.keepalive') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                clearInterval(countdownInterval);
                bsModal.hide();
                resetIdleTimer();
            })
            .catch(function () {
                window.location.href = "{{ route('login.index') }}?expired=1";
            })
            .finally(function () {
                btnMantener.disabled = false;
                btnMantener.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Mantener Sesión Activa';
            });
        });
    }

    // Cerrar sesión voluntario desde el modal
    if (btnCerrar) {
        btnCerrar.addEventListener('click', function () {
            ejecutarCierrePorInactividad();
        });
    }

    // Eventos de usuario para detectar actividad (con debounce)
    const userEvents = ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll'];
    let lastActivityLogged = Date.now();

    userEvents.forEach(function (eventName) {
        window.addEventListener(eventName, function () {
            const now = Date.now();
            if (now - lastActivityLogged > 2000) { // Throttled a cada 2 seg
                lastActivityLogged = now;
                resetIdleTimer();
            }
        }, { passive: true });
    });

    // Iniciar temporizador al cargar la página
    resetIdleTimer();
});
</script>
