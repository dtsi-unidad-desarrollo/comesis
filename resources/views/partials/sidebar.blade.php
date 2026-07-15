@php
    $url = explode('/', $_SERVER['REQUEST_URI']);
    $categoria = strtoupper($url[1]);
    if (isset($url[2])) {
        $subcategoria = strtoupper($url[2]);
    } else {
        $subcategoria = 'LISTA';
    }
@endphp

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Start Components Nav | Panel -->
        @if (Auth::user()->hasPermiso('panel'))
            <li class="nav-item">
                <a class="nav-link {{ url()->current() == route('admin.panel.index') ? 'bg-primary text-white' : 'collapsed' }} "
                    href="{{ route('admin.panel.index') }}">
                    <i class="bi bi-grid"></i>
                    <span>Panel</span>
                </a>
            </li>
        @endif

        <!-- Start Components Nav | Recepcion -->
        @if (Auth::user()->hasPermiso('recepcion'))
            <li class="nav-item">
                <a class="nav-link {{ url()->current() == route('admin.recepcion.index') ? 'bg-primary text-white' : 'collapsed' }}"
                    href="{{ route('admin.recepcion.index') }}">
                    <i class="bi bi-box fs-3"></i>
                    <span>Recepción</span>
                </a>
            </li>
        @endif

        <!-- Start Components Nav | comensales -->
        @if (Auth::user()->hasPermiso('comensales') || Auth::user()->hasPermiso('entradas'))
            <li class="nav-item">
                <a class="nav-link {{ url()->current() == route('admin.comensales.index') || url()->current() == route('admin.entradas.index') ? 'collapse show' : 'collapsed' }}"
                    data-bs-target="#components-nav-comensales" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people fs-3"></i><span>Comensales</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav-comensales"
                    class="nav-content {{ url()->current() == route('admin.comensales.index') || url()->current() == route('admin.entradas.index') ? 'collapse show' : 'collapse' }} "
                    data-bs-parent=" #sidebar-nav-1">
                    @if (Auth::user()->hasPermiso('comensales'))
                        <li>
                            <a class="nav-link {{ url()->current() == route('admin.comensales.index') ? 'bg-primary text-white' : '' }}"
                                href="{{ route('admin.comensales.index') }}">
                                <i class="bi bi-circle"></i><span>Lista</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermiso('entradas'))
                        <li>
                            <a class="nav-link {{ url()->current() == route('admin.entradas.index') ? 'bg-primary text-white' : '' }}"
                                href="{{ route('admin.entradas.index') }}">
                                <i class="bi bi-circle"></i><span>Historial de recepción</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </li>
        @endif

        <!-- Start Components Nav | configuraciones -->
        @if (Auth::user()->hasAnyPermiso(['configuracion']))
            <li class="nav-item">
                <a class="nav-link {{ in_array(url()->current(), [route('admin.users.index'), route('admin.users.create'), route('admin.roles.index'), route('admin.permisos.index'), route('admin.atms.index'), route('admin.servicios.index'), route('admin.sincronizar.data.index'), route('admin.auditoria.index')]) || request()->routeIs('admin.torniquetes.*') ? 'collapse show' : 'collapsed' }}"
                    data-bs-target="#components-nav-10" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-gear"></i><span>Configuración</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav-10"
                    class="nav-content {{ in_array(url()->current(), [route('admin.users.index'), route('admin.users.create'), route('admin.roles.index'), route('admin.permisos.index'), route('admin.atms.index'), route('admin.servicios.index'), route('admin.sincronizar.data.index'), route('admin.auditoria.index')]) || request()->routeIs('admin.torniquetes.*') ? 'collapse show' : 'collapse' }} "
                    data-bs-parent=" #sidebar-nav">

                    <!-- Start Components Nav | Servicios -->
                    @if (Auth::user()->hasPermiso('servicios'))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('admin.servicios.index') ? 'bg-primary text-white' : 'collapsed' }} "
                                href="{{ route('admin.servicios.index') }}">
                                <i class="bi bi-cup-straw fs-3"></i>
                                <span>Servicios</span>
                            </a>
                        </li>
                    @endif

                    <!-- Start Components Nav | usuarios -->
                    @if (Auth::user()->hasPermiso('users'))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('admin.users.index') || url()->current() == route('admin.users.create') ? 'collapse show' : 'collapsed' }}"
                                data-bs-target="#components-nav-1" data-bs-toggle="collapse" href="#">
                                <i class="bi bi-shield-lock fs-3"></i><span>Usuarios</span><i
                                    class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <ul id="components-nav-1"
                                class="nav-content {{ url()->current() == route('admin.users.index') || url()->current() == route('admin.users.create') ? 'collapse show' : 'collapse' }} "
                                data-bs-parent=" #sidebar-nav-1">
                                <li>
                                    <a class="nav-link {{ url()->current() == route('admin.users.index') ? 'bg-primary text-white' : '' }}"
                                        href="{{ route('admin.users.index') }}">
                                        <i class="bi bi-circle"></i><span>Lista</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link {{ url()->current() == route('admin.users.create') ? 'bg-primary text-white' : '' }}"
                                        href="{{ route('admin.users.create') }}">
                                        <i class="bi bi-circle"></i><span>Crear</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif

                    <!-- Start Components Nav | roles -->
                    @if (Auth::user()->hasPermiso('roles'))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('admin.roles.index') ? 'bg-primary text-white' : 'collapsed' }} "
                                href="{{ route('admin.roles.index') }}">
                                <i class="bi bi-person-badge fs-3"></i>
                                <span>Roles</span>
                            </a>
                        </li>
                    @endif

                    <!-- Start Components Nav | permisos -->
                    @if (Auth::user()->hasPermiso('permisos'))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('admin.permisos.index') ? 'bg-primary text-white' : 'collapsed' }} "
                                href="{{ route('admin.permisos.index') }}">
                                <i class="bi bi-key fs-3"></i>
                                <span>Permisos</span>
                            </a>
                        </li>
                    @endif

                    <!-- Start Components Nav | torniquetes -->
                    @if (Auth::user()->hasPermiso('torniquetes'))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('admin.torniquetes.index') ? 'bg-primary text-white' : 'collapsed' }} "
                                href="{{ route('admin.torniquetes.index') }}">
                                <i class="bi bi-door-open fs-3"></i>
                                <span>Torniquetes</span>
                            </a>
                        </li>
                    @endif

                    <!-- Start Components Nav | ATMs -->
                    @if (Auth::user()->hasPermiso('atms'))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('admin.atms.index') ? 'bg-primary text-white' : 'collapsed' }} "
                                href="{{ route('admin.atms.index') }}">
                                <i class="bi bi-cpu fs-3"></i>
                                <span>ATMs</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->hasPermiso('auditoria'))
                        <li class="nav-item">
                            <a class="nav-link {{ url()->current() == route('admin.auditoria.index') ? 'bg-primary text-white' : 'collapsed' }} "
                                href="{{ route('admin.auditoria.index') }}">
                                <i class="bi bi-journal-bookmark fs-3"></i>
                                <span>Auditoría</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </li><!-- End Components Nav | configuraciones -->

        @endif
    </ul>


</aside><!-- End Sidebar -->
