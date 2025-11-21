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
            <a class="nav-link {{ url()->current() == route('admin.users.index') ? 'collapse show' : 'collapsed' }}"
                data-bs-target="#components-nav-10" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i><span>Configuración</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav-10"
                class="nav-content {{ (url()->current() == route('admin.users.index')) || (url()->current() == route('admin.users.create'))  ? 'collapse show' : 'collapse' }} "
                data-bs-parent=" #sidebar-nav">

                <!-- Start Components Nav | Servicios -->
                <li class="nav-item">
                    <a class="nav-link {{ url()->current() == route('admin.servicios.index') ? 'bg-primary text-white' : 'collapsed' }}" 
                        href="{{ route('admin.servicios.index') }}" >
                        <i class="bi bi-cup-straw fs-3"></i>
                        <span>Servicios</span>
                    </a>
                </li><!-- End Dashboard Nav | Servicios-->
                
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

