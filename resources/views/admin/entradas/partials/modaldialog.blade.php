<!-- Botón de Ver Detalles -->
<button type="button" class="btn btn-sm btn-outline-primary rounded-circle p-0 d-inline-flex align-items-center justify-content-center"
        style="width: 32px; height: 32px;"
        data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{ $entrada->id }}"
        title="Ver detalles completos">
    <i class="bi bi-eye"></i>
</button>

<!-- Modal de Detalles de la Entrada -->
<div class="modal fade" id="modalDialogScrollable{{ $entrada->id }}" tabindex="-1" aria-labelledby="modalDetallesLabel{{ $entrada->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header py-3 text-white border-0" style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%);">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge fs-4 me-2"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalDetallesLabel{{ $entrada->id }}">Detalle de Entrada</h5>
                        <small class="text-white-50">Registro #{{ $entrada->id }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 text-start">
                <!-- Tarjeta Principal del Comensal -->
                <div class="text-center mb-4 pb-3 border-bottom">
                    @php
                        $inicial = strtoupper(substr(trim($entrada->nombres ?? 'U'), 0, 1));
                        $colorBg = match(strtoupper($entrada->tipo_comensal ?? '')) {
                            'ESTUDIANTE', 'ESTUDIANTE FORANEO' => '#0d6efd',
                            'EMPLEADO' => '#198754',
                            'APOYO' => '#f59e0b',
                            default => '#6c757d'
                        };
                    @endphp
                    <div class="rounded-circle text-white fw-bold d-inline-flex align-items-center justify-content-center mb-3 shadow"
                         style="width: 68px; height: 68px; background-color: {{ $colorBg }}; font-size: 26px;">
                        {{ $inicial }}
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $entrada->nombres }} {{ $entrada->apellidos }}</h4>
                    @php
                        $subtipoModal = $entrada->sub_tipo_display;
                    @endphp
                    <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                        <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace px-3 py-1 fs-6 border">
                            {{ $entrada->nacionalidad }}-{{ $entrada->cedula }}
                        </span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fs-6">
                            {{ $entrada->tipo_comensal }}
                        </span>
                        @if(!empty($subtipoModal))
                            <span class="badge bg-light text-dark border px-3 py-1 fs-6">
                                <i class="bi bi-tag-fill text-primary me-1"></i>Subtipo: <strong>{{ $subtipoModal }}</strong>
                            </span>
                        @endif
                        @if($entrada->sexo)
                            <span class="badge bg-light text-dark border px-2 py-1">
                                Género: <strong>{{ $entrada->sexo }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Bloques de Información -->
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="text-uppercase text-secondary small fw-bold mb-2" style="letter-spacing: .5px;">
                            <i class="bi bi-clock-history text-primary me-1"></i> Información del Servicio
                        </h6>
                    </div>

                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <small class="text-muted d-block">Servicio</small>
                            <strong class="text-dark">{{ $entrada->comida }}</strong>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <small class="text-muted d-block">Subtipo Registrado</small>
                            <strong class="text-dark">{{ $subtipoModal ?: 'Sin subtipo' }}</strong>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <small class="text-muted d-block">Fecha y Hora</small>
                            <strong class="text-dark">{{ \Carbon\Carbon::parse($entrada->fecha)->format('d/m/Y') }} {{ $entrada->hora }}</strong>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <small class="text-muted d-block">Taquilla / Terminal</small>
                            <strong class="text-dark">{{ $entrada->atm->nombre ?? 'N/A' }}</strong>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <small class="text-muted d-block">Taquillero</small>
                            <strong class="text-dark">{{ $entrada->user->nombre ?? 'N/A' }}</strong>
                        </div>
                    </div>

                    @if($entrada->carrera || $entrada->sede)
                        <div class="col-12 mt-3">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-2" style="letter-spacing: .5px;">
                                <i class="bi bi-mortarboard text-primary me-1"></i> Datos Académicos / Institucionales
                            </h6>
                        </div>

                        @if($entrada->carrera)
                        <div class="col-12">
                            <div class="p-2 bg-light rounded-3 border">
                                <small class="text-muted d-block">Carrera / Programa</small>
                                <strong class="text-dark">{{ $entrada->carrera }}</strong>
                            </div>
                        </div>
                        @endif

                        @if($entrada->sede)
                        <div class="col-12">
                            <div class="p-2 bg-light rounded-3 border">
                                <small class="text-muted d-block">Sede</small>
                                <strong class="text-dark">{{ $entrada->sede }}</strong>
                            </div>
                        </div>
                        @endif
                    @endif

                    @if($entrada->direccion || $entrada->municipio || $entrada->estado)
                        <div class="col-12 mt-3">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-2" style="letter-spacing: .5px;">
                                <i class="bi bi-geo-alt text-primary me-1"></i> Ubicación
                            </h6>
                            <div class="p-2 bg-light rounded-3 border small">
                                <div><strong>Estado:</strong> {{ $entrada->estado ?? 'N/A' }} | <strong>Municipio:</strong> {{ $entrada->municipio ?? 'N/A' }}</div>
                                @if($entrada->direccion)
                                    <div><strong>Dirección:</strong> {{ $entrada->direccion }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="modal-footer bg-light border-0 py-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>