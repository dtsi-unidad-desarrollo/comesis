@php
    $isComensalesActive = request()->routeIs('admin.comensales.*') || request()->routeIs('admin.apoyos.*') || request()->routeIs('admin.entradas.*');
    $isConfigActive = request()->routeIs('admin.servicios.*') || request()->routeIs('admin.users.*') || 
                      request()->routeIs('admin.roles.*') || request()->routeIs('admin.permisos.*') || 
                      request()->routeIs('admin.torniquetes.*') || request()->routeIs('admin.atms.*') || 
                      request()->routeIs('admin.auditoria.*') || request()->routeIs('admin.allowed-ips.*') || 
                      request()->routeIs('admin.sincronizar.*');
    $isUsersActive = request()->routeIs('admin.users.*');
@endphp

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar d-flex flex-column justify-content-between">

    <div class="sidebar-wrapper">
        <ul class="sidebar-nav" id="sidebar-nav">

            <!-- SECCIÓN: PRINCIPAL -->
            <li class="nav-heading">Principal</li>

            <!-- Item: Panel -->
            @if (Auth::user()->hasPermiso('panel'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.panel.*') ? 'active-link' : 'collapsed' }}"
                        href="{{ route('admin.panel.index') }}">
                        <span class="nav-icon-wrap">
                            <i class="bi bi-grid-fill"></i>
                        </span>
                        <span>Panel de Control</span>
                    </a>
                </li>
            @endif

            <!-- Item: Recepción -->
            @if (Auth::user()->hasPermiso('recepcion'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.recepcion.*') ? 'active-link' : 'collapsed' }}"
                        href="{{ route('admin.recepcion.index') }}">
                        <span class="nav-icon-wrap">
                            <i class="bi bi-qr-code-scan"></i>
                        </span>
                        <span>Punto de Control</span>
                    </a>
                </li>
            @endif

            <!-- SECCIÓN: GESTIÓN DE COMEDOR -->
            @if (Auth::user()->hasPermiso('comensales') || Auth::user()->hasPermiso('entradas'))
                <li class="nav-heading mt-3">Gestión de Comedor</li>

                <li class="nav-item">
                    <a class="nav-link {{ $isComensalesActive ? '' : 'collapsed' }}"
                        data-bs-target="#nav-comensales" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ $isComensalesActive ? 'true' : 'false' }}">
                        <span class="nav-icon-wrap">
                            <i class="bi bi-people-fill"></i>
                        </span>
                        <span>Comensales</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul id="nav-comensales"
                        class="nav-content collapse {{ $isComensalesActive ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        
                        @if (Auth::user()->hasPermiso('comensales'))
                            <li>
                                <a class="{{ request()->routeIs('admin.comensales.*') ? 'active' : '' }}"
                                    href="{{ route('admin.comensales.index') }}">
                                    <i class="bi bi-person-lines-fill"></i>
                                    <span>Lista General</span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ request()->routeIs('admin.apoyos.*') ? 'active' : '' }}"
                                    href="{{ route('admin.apoyos.index') }}">
                                    <i class="bi bi-ticket-detailed-fill"></i>
                                    <span>Apoyos Institucionales</span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->hasPermiso('entradas'))
                            <li>
                                <a class="{{ request()->routeIs('admin.entradas.*') ? 'active' : '' }}"
                                    href="{{ route('admin.entradas.index') }}">
                                    <i class="bi bi-clock-history"></i>
                                    <span>Historial de Entradas</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- SECCIÓN: ADMINISTRACIÓN Y SISTEMA -->
            @if (Auth::user()->hasAnyPermiso(['configuracion']))
                <li class="nav-heading mt-3">Sistema</li>

                <li class="nav-item">
                    <a class="nav-link {{ $isConfigActive ? '' : 'collapsed' }}"
                        data-bs-target="#nav-configuracion" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ $isConfigActive ? 'true' : 'false' }}">
                        <span class="nav-icon-wrap">
                            <i class="bi bi-gear-wide-connected"></i>
                        </span>
                        <span>Configuración</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <ul id="nav-configuracion"
                        class="nav-content collapse {{ $isConfigActive ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">

                        <!-- Servicios -->
                        @if (Auth::user()->hasPermiso('servicios'))
                            <li>
                                <a class="{{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}"
                                    href="{{ route('admin.servicios.index') }}">
                                    <i class="bi bi-cup-hot-fill"></i>
                                    <span>Servicios y Turnos</span>
                                </a>
                            </li>
                        @endif

                        <!-- Submenú: Usuarios -->
                        @if (Auth::user()->hasPermiso('users'))
                            <li>
                                <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                    href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-person-badge-fill"></i>
                                    <span>Gestión de Usuarios</span>
                                </a>
                            </li>
                        @endif

                        <!-- Roles -->
                        @if (Auth::user()->hasPermiso('roles'))
                            <li>
                                <a class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                                    href="{{ route('admin.roles.index') }}">
                                    <i class="bi bi-shield-shaded"></i>
                                    <span>Roles de Usuario</span>
                                </a>
                            </li>
                        @endif

                        <!-- Permisos -->
                        @if (Auth::user()->hasPermiso('permisos'))
                            <li>
                                <a class="{{ request()->routeIs('admin.permisos.*') ? 'active' : '' }}"
                                    href="{{ route('admin.permisos.index') }}">
                                    <i class="bi bi-key-fill"></i>
                                    <span>Permisos y Accesos</span>
                                </a>
                            </li>
                        @endif

                        <!-- Torniquetes -->
                        @if (Auth::user()->hasPermiso('torniquetes'))
                            <li>
                                <a class="{{ request()->routeIs('admin.torniquetes.*') ? 'active' : '' }}"
                                    href="{{ route('admin.torniquetes.index') }}">
                                    <i class="bi bi-door-open-fill"></i>
                                    <span>Torniquetes Físicos</span>
                                </a>
                            </li>
                        @endif

                        <!-- ATMs -->
                        @if (Auth::user()->hasPermiso('atms'))
                            <li>
                                <a class="{{ request()->routeIs('admin.atms.*') ? 'active' : '' }}"
                                    href="{{ route('admin.atms.index') }}">
                                    <i class="bi bi-cpu-fill"></i>
                                    <span>Taquillas / ATMs</span>
                                </a>
                            </li>
                        @endif

                        <!-- Auditoría -->
                        @if (Auth::user()->hasPermiso('auditoria'))
                            <li>
                                <a class="{{ request()->routeIs('admin.auditoria.*') ? 'active' : '' }}"
                                    href="{{ route('admin.auditoria.index') }}">
                                    <i class="bi bi-journal-text"></i>
                                    <span>Registro de Auditoría</span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ request()->routeIs('admin.allowed-ips.*') ? 'active' : '' }}"
                                    href="{{ route('admin.allowed-ips.index') }}">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Lista Blanca de IPs</span>
                                </a>
                            </li>
                        @endif

                        <!-- Sincronización DUX / Terepaima -->
                        @if (Auth::user()->hasAnyPermiso(['configuracion', 'comensales']))
                            <li>
                                <a class="{{ request()->routeIs('admin.sincronizar.*') ? 'active' : '' }}"
                                    href="{{ route('admin.sincronizar.data.index') }}">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>Sincronización Remota</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

        </ul>
    </div>

    <!-- Footer del Sidebar / Tarjeta de Sistema -->
    <div class="sidebar-footer mt-4 pt-3 border-top">
        <div class="card border-0 bg-light rounded-3 p-3">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold" style="font-size: 10px;">
                    COMESIS v2.5
                </span>
                <span class="badge bg-success-subtle text-success d-inline-flex align-items-center" style="font-size: 10px;">
                    <span class="rounded-circle bg-success me-1" style="width: 6px; height: 6px;"></span> Activo
                </span>
            </div>
            <div class="small fw-semibold text-dark text-truncate">UNELLEZ Comedor</div>
            <small class="text-muted" style="font-size: 11px;">Control integral de accesos</small>
        </div>
    </div>

</aside><!-- End Sidebar -->
