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
        @if (Auth::user()->rol == 1 || Auth::user()->rol == 2)
        <!-- Start Components Nav | Panel -->
        <li class="nav-item">
            <a class="nav-link {{ url()->current() == route('admin.panel.index') ? 'bg-primary text-white' : 'collapsed' }} " 
                href="{{ route('admin.panel.index') }}" >
                <i class="bi bi-grid"></i>
                <span>Panel</span>
            </a>
        </li><!-- End Dashboard Nav | Panel-->

            <!-- Start Components Nav | Recepcion -->
            <li class="nav-item">
                <a class="nav-link {{ url()->current() == route('admin.recepcion.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                    href="{{ route('admin.recepcion.index') }}" >
                    <i class="bi bi-box fs-3"></i>
                    <span>Recepción</span>
                </a>
            </li><!-- End Nav | Recepcion-->
        

          <!-- Start Components Nav | comensales -->
          <li class="nav-item">
            <a class="nav-link {{ ( url()->current() == route('admin.comensales.index') ) || ( url()->current() == route('admin.entradas.index') ) ? 'collapse show' : 'collapsed' }}"
                data-bs-target="#components-nav-comensales" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people fs-3"></i><span>Comensales</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav-comensales"
                class="nav-content {{ ( url()->current() == route('admin.comensales.index') ) || (url()->current() == route('admin.entradas.index')) ?  'collapse show' : 'collapse' }} "
                data-bs-parent=" #sidebar-nav-1">
                <li>
                    <a class="nav-link {{ url()->current() == route('admin.comensales.index') ? 'bg-primary text-white' : '' }}"
                        href="{{ route('admin.comensales.index') }}" >
                        <i class="bi bi-circle"></i><span>Lista</span>
                    </a>
                </li>
                <li>
                    <a  class="nav-link {{ url()->current() == route('admin.entradas.index') ? 'bg-primary text-white' : '' }}"
                        href="{{ route('admin.entradas.index') }}">
                        <i class="bi bi-circle"></i><span>Historial de recepción</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Components Nav | comensales -->

        <!-- Start Components Nav | configuraciones -->
        <li class="nav-item">
            <a class="nav-link {{ ( in_array(url()->current(), [route('admin.users.index'), route('admin.users.create'), route('admin.roles.index'), route('admin.permisos.index'), route('admin.atms.index'), route('admin.servicios.index'), route('admin.sincronizar.data.index')]) || request()->routeIs('admin.torniquetes.*') ) ? 'collapse show' : 'collapsed' }}"
                data-bs-target="#components-nav-10" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i><span>Configuración</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav-10"
                class="nav-content {{ ( in_array(url()->current(), [route('admin.users.index'), route('admin.users.create'), route('admin.roles.index'), route('admin.permisos.index'), route('admin.atms.index'), route('admin.servicios.index'), route('admin.sincronizar.data.index')]) || request()->routeIs('admin.torniquetes.*') ) ? 'collapse show' : 'collapse' }} "
                data-bs-parent=" #sidebar-nav">

                <!-- Start Components Nav | Servicios -->
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() == route('admin.servicios.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                        href="{{ route('admin.servicios.index') }}" >
                        <i class="bi bi-cup-straw fs-3"></i>
                        <span>Servicios</span>
                    </a>
                </li><!-- End Dashboard Nav | Servicios-->

                <!-- Start Components Nav | Sincronizar Data -->
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() == route('admin.sincronizar.data.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                        href="{{ route('admin.sincronizar.data.index') }}" >
                        <i class="bi bi-arrow-repeat fs-3"></i>
                        <span>Sincronizar Data</span>
                    </a>
                </li><!-- End Dashboard Nav | Sincronizar Data-->
                
                <!-- Start Components Nav | usuarios -->
                <li class="nav-item">
                    <a class="nav-link {{ (url()->current() == route('admin.users.index')) || (url()->current() == route('admin.users.create')) ? 'collapse show' : 'collapsed' }}"
                        data-bs-target="#components-nav-1" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-shield-lock fs-3"></i><span>Usuarios</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="components-nav-1"
                        class="nav-content {{ (url()->current() == route('admin.users.index')) || (url()->current() == route('admin.users.create'))  ? 'collapse show' : 'collapse' }} "
                        data-bs-parent=" #sidebar-nav-1">
                        <li>
                            <a class="nav-link {{ url()->current() == route('admin.users.index') ? 'bg-primary text-white' : '' }}"
                                href="{{ route('admin.users.index') }}" >
                                <i class="bi bi-circle"></i><span>Lista</span>
                            </a>
                        </li>
                        <li>
                            <a  class="nav-link {{ url()->current() == route('admin.users.create') ? 'bg-primary text-white' : '' }}"
                                href="{{ route('admin.users.create') }}">
                                <i class="bi bi-circle"></i><span>Crear</span>
                            </a>
                        </li>

                    </ul>
                </li><!-- End Components Nav | usuarios -->
                
                <!-- Start Components Nav | roles -->
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() == route('admin.roles.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                        href="{{ route('admin.roles.index') }}" >
                        <i class="bi bi-person-badge fs-3"></i>
                        <span>Roles</span>
                    </a>
                </li><!-- End Components Nav | roles-->

                <!-- Start Components Nav | permisos -->
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() == route('admin.permisos.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                        href="{{ route('admin.permisos.index') }}" >
                        <i class="bi bi-key fs-3"></i>
                        <span>Permisos</span>
                    </a>
                </li><!-- End Components Nav | permisos-->

                <!-- Start Components Nav | torniquetes -->
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() == route('admin.torniquetes.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                        href="{{ route('admin.torniquetes.index') }}" >
                        <i class="bi bi-door-open fs-3"></i>
                        <span>Torniquetes</span>
                    </a>
                </li><!-- End Components Nav | torniquetes-->

                <!-- Start Components Nav | ATMs -->
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() == route('admin.atms.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                        href="{{ route('admin.atms.index') }}" >
                        <i class="bi bi-cpu fs-3"></i>
                        <span>ATMs</span>
                    </a>
                </li><!-- End Components Nav | ATMs-->

            </ul>
        </li><!-- End Components Nav | configuraciones -->
        
        <!----------------------------------------- MENU CAJERO ------------------------------------------------------->
        @elseif(Auth::user()->rol == 3)
            <!-- Start Components Nav | Recepcion -->
            <li class="nav-item">
                <a class="nav-link {{ url()->current() == route('admin.recepcion.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                    href="{{ route('admin.recepcion.index') }}" >
                    <i class="bi bi-play fs-3
                    {{ url()->current() == route('admin.recepcion.index') ? 'text-white' : '' }}
                    "></i>
                    <span>Recepción</span>
                </a>
            </li><!-- End Nav | Recepcion-->

             <!-- Start Components Nav | Historial -->
             <li class="nav-item">
                <a  class="nav-link {{ url()->current() == route('admin.entradas.index') ? 'bg-primary text-white' : 'collapsed' }}"
                    href="{{ route('admin.entradas.index') }}">
                    <i class="bi bi-card-checklist 
                    {{ url()->current() == route('admin.entradas.index') ? 'text-white' : '' }}
                    fs-3"></i><span>Historial de recepción</span>
                </a>
            </li><!-- End Dashboard Nav | Historial-->
        @endif

    </ul>


</aside><!-- End Sidebar -->

