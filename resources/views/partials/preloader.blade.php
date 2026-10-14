{{-- Componente Global de Preloader Profesional COMESIS --}}
<div id="comesis-global-preloader" class="comesis-preloader-root" aria-hidden="true">
    <!-- Barra de progreso superior continua / indeterminada -->
    <div class="comesis-progress-track">
        <div class="comesis-progress-bar"></div>
    </div>

    <!-- Telón de fondo con desenfoque de cristal (Glassmorphism) -->
    <div class="comesis-preloader-backdrop">
        <div class="comesis-preloader-card">
            <!-- Icono 3D con halo giratorio y efecto pulso -->
            <div class="comesis-preloader-brand">
                <div class="comesis-spinner-ring"></div>
                <div class="comesis-spinner-ring-outer"></div>
                <img src="{{ asset('assets/img/icons/icon-192x192.png') }}" alt="COMESIS" class="comesis-preloader-icon">
            </div>

            <!-- Título y subtítulo dinámicos -->
            <div class="comesis-preloader-content">
                <h5 class="comesis-preloader-title" id="comesis-preloader-title">Cargando sección...</h5>
                <p class="comesis-preloader-subtitle" id="comesis-preloader-subtitle">Por favor espere un momento</p>
            </div>

            <!-- Puntos animados en onda -->
            <div class="comesis-preloader-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos encapsulados del Preloader Global COMESIS */
    .comesis-preloader-root {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999999;
        pointer-events: none;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.22s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.22s ease;
    }

    .comesis-preloader-root.active {
        opacity: 1;
        visibility: visible;
        pointer-events: all;
    }

    .comesis-preloader-root.hiding {
        opacity: 0;
        visibility: visible;
        pointer-events: none;
    }

    /* Barra superior de progreso */
    .comesis-progress-track {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 3.5px;
        background: rgba(13, 110, 253, 0.15);
        overflow: hidden;
        z-index: 10000000;
    }

    .comesis-progress-bar {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(90deg, #0d6efd 0%, #00d2ff 50%, #2563eb 100%);
        box-shadow: 0 0 12px rgba(13, 110, 253, 0.8), 0 0 4px #00d2ff;
        animation: comesis-indeterminate 1.5s cubic-bezier(0.65, 0.815, 0.735, 0.395) infinite;
        transform-origin: left;
    }

    @keyframes comesis-indeterminate {
        0% { transform: translateX(-100%) scaleX(0.2); }
        50% { transform: translateX(0%) scaleX(0.7); }
        100% { transform: translateX(100%) scaleX(0.2); }
    }

    /* Telón difuminado tipo Glassmorphism */
    .comesis-preloader-backdrop {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.52);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        padding: 1.5rem;
    }

    /* Tarjeta flotante moderna */
    .comesis-preloader-card {
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(255, 255, 255, 0.85);
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(13, 110, 253, 0.08);
        border-radius: 24px;
        padding: 2.2rem 2.4rem;
        max-width: 350px;
        width: 100%;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        transform: scale(0.92);
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .comesis-preloader-root.active .comesis-preloader-card {
        transform: scale(1);
    }

    /* Contenedor del Icono con Anillos */
    .comesis-preloader-brand {
        position: relative;
        width: 86px;
        height: 86px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }

    .comesis-preloader-icon {
        width: 48px;
        height: 48px;
        object-fit: contain;
        position: relative;
        z-index: 2;
        filter: drop-shadow(0 4px 8px rgba(13, 110, 253, 0.3));
        animation: comesis-logo-pulse 2s ease-in-out infinite;
    }

    .comesis-spinner-ring {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 50%;
        border: 3px solid rgba(13, 110, 253, 0.12);
        border-top-color: #0d6efd;
        border-right-color: #00d2ff;
        animation: comesis-spin 1s cubic-bezier(0.55, 0.15, 0.45, 0.85) infinite;
        z-index: 1;
    }

    .comesis-spinner-ring-outer {
        position: absolute;
        top: -6px;
        left: -6px;
        right: -6px;
        bottom: -6px;
        border-radius: 50%;
        border: 1.5px dashed rgba(13, 110, 253, 0.3);
        animation: comesis-spin-reverse 6s linear infinite;
        z-index: 0;
    }

    @keyframes comesis-spin {
        to { transform: rotate(360deg); }
    }

    @keyframes comesis-spin-reverse {
        to { transform: rotate(-360deg); }
    }

    @keyframes comesis-logo-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); filter: drop-shadow(0 6px 12px rgba(13, 110, 253, 0.5)); }
    }

    /* Textos informativos */
    .comesis-preloader-content {
        margin-bottom: 0.75rem;
    }

    .comesis-preloader-title {
        color: #0f172a;
        font-weight: 700;
        font-size: 1.15rem;
        margin-bottom: 0.35rem;
        letter-spacing: -0.2px;
    }

    .comesis-preloader-subtitle {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0;
        line-height: 1.4;
    }

    /* Puntos en onda */
    .comesis-preloader-dots {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
    }

    .comesis-preloader-dots span {
        width: 7px;
        height: 7px;
        background-color: #0d6efd;
        border-radius: 50%;
        display: inline-block;
        animation: comesis-dot-wave 1.4s infinite ease-in-out both;
    }

    .comesis-preloader-dots span:nth-child(1) { animation-delay: -0.32s; }
    .comesis-preloader-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes comesis-dot-wave {
        0%, 80%, 100% { transform: scale(0.4); opacity: 0.3; }
        40% { transform: scale(1); opacity: 1; }
    }
</style>

<script>
    (function() {
        var overlay = null;
        var titleEl = null;
        var subtitleEl = null;
        var safetyTimer = null;
        var activeSubmitBtn = null;

        function getElements() {
            if (!overlay) {
                overlay = document.getElementById('comesis-global-preloader');
                if (overlay) {
                    titleEl = document.getElementById('comesis-preloader-title');
                    subtitleEl = document.getElementById('comesis-preloader-subtitle');
                }
            }
            return overlay;
        }

        window.ComesisPreloader = {
            show: function(title, subtitle) {
                if (!getElements()) return;
                if (title && titleEl) titleEl.textContent = title;
                if (subtitle && subtitleEl) subtitleEl.textContent = subtitle;

                overlay.classList.remove('hiding');
                overlay.classList.add('active');

                // Timer de seguridad: si una descarga de archivo o respuesta local no recarga la página, liberar a los 7.5s
                clearTimeout(safetyTimer);
                safetyTimer = setTimeout(function() {
                    window.ComesisPreloader.hide();
                }, 7500);
            },

            hide: function() {
                clearTimeout(safetyTimer);
                if (!getElements()) return;
                overlay.classList.add('hiding');
                setTimeout(function() {
                    overlay.classList.remove('active', 'hiding');
                    if (activeSubmitBtn) {
                        if (activeSubmitBtn.dataset.originalContent) {
                            activeSubmitBtn.innerHTML = activeSubmitBtn.dataset.originalContent;
                        }
                        activeSubmitBtn.disabled = false;
                        activeSubmitBtn = null;
                    }
                }, 220);
            },

            setLoadingBtn: function(btn, label) {
                if (!btn) return;
                activeSubmitBtn = btn;
                btn.dataset.originalContent = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' + (label || 'Procesando...');
            }
        };

        // Ocultar preloader cuando la página termine de cargar o se vuelva con el historial (BFCache)
        window.addEventListener('pageshow', function() {
            window.ComesisPreloader.hide();
        });

        window.addEventListener('DOMContentLoaded', function() {
            window.ComesisPreloader.hide();
        });

        // 1. Intercepción de Envíos de Formulario (Login, CRUD, Búsquedas, Recepción)
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            // Si el submit fue cancelado por validación client-side
            if (e.defaultPrevented) return;

            // Ignorar formularios con apertura en nueva ventana o con bypass explícito
            if (form.target === '_blank' || form.hasAttribute('data-no-preload') || form.classList.contains('no-preload')) {
                return;
            }

            // Validar HTML5 si aplica
            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                return;
            }

            var submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            var action = (form.getAttribute('action') || '').toLowerCase();
            var method = (form.getAttribute('method') || 'GET').toUpperCase();
            var isDelete = form.querySelector('input[name="_method"][value="DELETE"]') || form.querySelector('input[name="_method"][value="delete"]');

            var isLogin = action.includes('login') || form.id === 'loginForm' || document.getElementById('yourUsername');
            var isLogout = action.includes('logout');
            var isRecepcion = action.includes('recepcion') || form.querySelector('#cedula');
            var isSync = action.includes('sincronizar') || action.includes('sync');
            var isGetSearch = method === 'GET';

            var title = 'Procesando solicitud...';
            var subtitle = 'Guardando cambios en el sistema';
            var btnText = 'Procesando...';

            if (isLogin) {
                title = 'Iniciando sesión...';
                subtitle = 'Verificando credenciales oficiales';
                btnText = 'Autenticando...';
            } else if (isLogout) {
                title = 'Cerrando sesión...';
                subtitle = 'Finalizando la sesión de forma segura';
                btnText = 'Saliendo...';
            } else if (isRecepcion) {
                title = 'Consultando comensal...';
                subtitle = 'Verificando datos y servicio en vivo';
                btnText = 'Consultando...';
            } else if (isSync) {
                title = 'Sincronizando información...';
                subtitle = 'Conectando con bases de datos UNELLEZ';
                btnText = 'Sincronizando...';
            } else if (isDelete) {
                title = 'Eliminando registro...';
                subtitle = 'Actualizando la base de datos';
                btnText = 'Eliminando...';
            } else if (isGetSearch) {
                title = 'Filtrando registros...';
                subtitle = 'Consultando la base de datos';
                btnText = 'Buscando...';
            }

            // Soporte para data attributes personalizados en el form
            if (form.dataset.loadingTitle) title = form.dataset.loadingTitle;
            if (form.dataset.loadingSubtitle) subtitle = form.dataset.loadingSubtitle;
            if (form.dataset.loadingBtn) btnText = form.dataset.loadingBtn;

            window.ComesisPreloader.setLoadingBtn(submitBtn, btnText);
            window.ComesisPreloader.show(title, subtitle);
        }, false);

        // 2. Intercepción de Clics en Enlaces / Redirección entre Secciones
        document.addEventListener('click', function(e) {
            // Ignorar clics secundarios (derecho) o combinaciones de teclas para abrir en pestaña nueva
            if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

            var link = e.target.closest('a');
            if (!link) return;

            var href = link.getAttribute('href');
            if (!href || href === '#' || href.indexOf('#') === 0 || href.indexOf('javascript:') === 0) return;

            // Ignorar enlaces externos o descargas
            if (link.target === '_blank' || link.hasAttribute('download') || link.hasAttribute('data-no-preload')) return;

            // Ignorar toggles de Bootstrap (modales, dropdowns, colapsos, pestañas)
            if (link.hasAttribute('data-bs-toggle') || link.hasAttribute('data-toggle')) return;

            // Comprobar que sea del mismo origen
            try {
                var url = new URL(link.href, window.location.href);
                if (url.origin !== window.location.origin) return;

                // Si es la misma página exacta con un hash interno
                if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;

                // Ignorar descargas de documentos o backups
                var path = url.pathname.toLowerCase();
                if (path.endsWith('.pdf') || path.endsWith('.xlsx') || path.endsWith('.xls') || path.endsWith('.csv') || path.endsWith('.sql') || path.endsWith('.zip')) {
                    return;
                }

                var title = 'Cargando sección...';
                var subtitle = 'Preparando la vista del sistema';

                if (path.indexOf('recepcion') !== -1) {
                    title = 'Punto de Control';
                    subtitle = 'Accediendo a taquilla y torniquetes';
                } else if (path.indexOf('panel') !== -1 || path.indexOf('dashboard') !== -1) {
                    title = 'Panel de Control';
                    subtitle = 'Cargando métricas y estadísticas';
                } else if (path.indexOf('comensales') !== -1) {
                    title = 'Padrón de Comensales';
                    subtitle = 'Accediendo al registro de usuarios';
                } else if (path.indexOf('entradas') !== -1) {
                    title = 'Historial de Entradas';
                    subtitle = 'Cargando registros de comedor';
                } else if (path.indexOf('apoyos') !== -1) {
                    title = 'Apoyos Institucionales';
                    subtitle = 'Cargando convenios y cupos';
                } else if (path.indexOf('configuracion') !== -1 || path.indexOf('servicios') !== -1 || path.indexOf('users') !== -1 || path.indexOf('roles') !== -1 || path.indexOf('auditoria') !== -1) {
                    title = 'Módulo de Configuración';
                    subtitle = 'Cargando parámetros del sistema';
                } else if (path.indexOf('perfil') !== -1) {
                    title = 'Perfil de Usuario';
                    subtitle = 'Cargando datos de la cuenta';
                }

                if (link.dataset.loadingTitle) title = link.dataset.loadingTitle;
                if (link.dataset.loadingSubtitle) subtitle = link.dataset.loadingSubtitle;

                window.ComesisPreloader.show(title, subtitle);
            } catch (err) {
                // Si la URL no es válida, continuar normalmente
            }
        }, false);
    })();
</script>
