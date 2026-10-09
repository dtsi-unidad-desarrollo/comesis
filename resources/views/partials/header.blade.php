<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center justify-content-between px-3 px-md-4">

    <!-- Lado Izquierdo: Brand & Toggle Sidebar -->
    <div class="d-flex align-items-center gap-2 gap-md-3">
        <a href="{{ route('admin.panel.index') }}" class="d-flex align-items-center text-decoration-none py-1">
            <img src="{{ asset('assets/img/logo.png') }}" alt="COMESIS" style="height: 36px; width: auto; object-fit: contain;">
        </a>

        <button type="button" class="toggle-sidebar-btn shadow-none" title="Alternar menú lateral" aria-label="Alternar menú">
            <i class="bi bi-list"></i>
        </button>

        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill d-none d-lg-inline-flex align-items-center px-2 py-1" style="font-size: 11px; letter-spacing: 0.5px;">
            <i class="bi bi-shield-check me-1"></i> UNELLEZ
        </span>
    </div><!-- End Logo & Toggle -->

    <!-- Centro: Atajos Rápidos / Estado -->
    <div class="d-none d-md-flex align-items-center gap-2">
        @if (Auth::user() && Auth::user()->hasPermiso('recepcion'))
            <a href="{{ route('admin.recepcion.index') }}" 
               class="btn btn-sm {{ request()->routeIs('admin.recepcion.*') ? 'btn-primary text-white' : 'btn-light text-primary border' }} rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center shadow-sm">
                <i class="bi bi-qr-code-scan me-1"></i> Punto de Control
            </a>
        @endif

        <div class="d-none d-xl-flex align-items-center text-secondary small bg-light px-3 py-1 rounded-pill border">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            <span class="fw-medium text-dark">{{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM') }}</span>
        </div>
    </div>

    <!-- Lado Derecho: Navegación y Perfil -->
    <nav class="header-nav">
        <ul class="d-flex align-items-center gap-2 mb-0 ps-0 list-unstyled">

            <!-- Perfil de Usuario -->
            <li class="nav-item dropdown pe-2">
                <a class="nav-link nav-profile d-flex align-items-center p-1 rounded-pill text-decoration-none" 
                   href="#" data-bs-toggle="dropdown" aria-expanded="false" style="transition: all 0.2s;">
                    
                    {{-- Avatar con indicador de conectado --}}
                    <div class="position-relative d-inline-block">
                        @php
                            $userFoto = Auth::user()->foto ?? null;
                            $fotoValida = $userFoto && file_exists(public_path($userFoto));
                        @endphp
                        @if ($fotoValida)
                            <img src="{{ asset($userFoto) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 36px; height: 36px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; font-size: 14px;">
                                {{ strtoupper(substr(Auth::user()->nombre ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;" title="En línea"></span>
                    </div>

                    <div class="d-none d-md-flex flex-column text-start ps-2 pe-1">
                        <span class="fw-bold text-dark lh-1" style="font-size: 13px;">
                            {{ Str::limit(Auth::user()->nombre ?? 'Usuario', 18) }}
                        </span>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ Auth::user()->rol == 1 ? 'Administrador' : 'Asistente' }}
                        </small>
                    </div>

                    <i class="bi bi-chevron-down text-muted small ms-1 d-none d-md-inline-block"></i>
                </a>

                <!-- Dropdown Menú del Perfil -->
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 py-2" style="min-width: 250px;">
                    <!-- Cabecera de tarjeta de usuario -->
                    <li class="px-3 py-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            @if ($fotoValida)
                                <img src="{{ asset($userFoto) }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 44px; height: 44px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; font-size: 16px;">
                                    {{ strtoupper(substr(Auth::user()->nombre ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="overflow-hidden">
                                <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 14px;">
                                    {{ Auth::user()->nombre ?? 'Usuario' }}
                                </h6>
                                <span class="badge {{ Auth::user()->rol == 1 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-info-subtle text-info border border-info-subtle' }} px-2 py-0 mt-1" style="font-size: 10px;">
                                    {{ Auth::user()->rol == 1 ? 'Administrador' : 'Asistente' }}
                                </span>
                            </div>
                        </div>
                        @if(Auth::user()->email)
                            <small class="text-muted d-block mt-2 text-truncate" style="font-size: 11px;">
                                <i class="bi bi-envelope me-1"></i>{{ Auth::user()->email }}
                            </small>
                        @endif
                    </li>

                    <!-- Enlaces de acción -->
                    <li class="pt-2">
                        <a class="dropdown-item py-2 d-flex align-items-center text-secondary" href="{{ route('admin.perfil.index') }}">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 text-primary" style="width: 28px; height: 28px;">
                                <i class="bi bi-person-gear"></i>
                            </div>
                            <span class="fw-medium text-dark">Mi Perfil</span>
                        </a>
                    </li>

                    @if(Auth::user()->hasPermiso('panel'))
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center text-secondary" href="{{ route('admin.panel.index') }}">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 text-success" style="width: 28px; height: 28px;">
                                    <i class="bi bi-speedometer2"></i>
                                </div>
                                <span class="fw-medium text-dark">Panel de Control</span>
                            </a>
                        </li>
                    @endif

                    <li><hr class="dropdown-divider my-2"></li>

                    <!-- Salir / Cerrar Sesión -->
                    <li class="px-2">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger-subtle text-danger w-100 rounded-3 py-2 d-flex align-items-center justify-content-center fw-semibold border-0" style="font-size: 13px;">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Navigation -->

</header><!-- End Header -->
