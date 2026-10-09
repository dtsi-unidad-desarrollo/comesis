 <!-- Modal Dialog Scrollable -->
 <button type="button" class="btn btn-sm btn-outline-primary rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$comensal->id}}" title="Ver ficha del comensal">
    <i class="bi bi-eye"></i>
 </button>
  <div class="modal fade" id="modalDialogScrollable{{$comensal->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content rounded-4 border-0 shadow">
        <div class="modal-header bg-primary text-white py-3">
          <h5 class="modal-title fw-bold">
            <i class="bi bi-person-badge me-2"></i>Ficha del Comensal
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="text-center mb-4">
            @if ($comensal->foto && file_exists(public_path($comensal->foto)))
              <img src="{{ asset($comensal->foto) }}" alt="Foto" class="rounded-circle shadow-sm border border-3 border-light mb-3" style="width: 100px; height: 100px; object-fit: cover;">
            @else
              @php
                $inicial = strtoupper(substr(trim($comensal->nombres ?? 'C'), 0, 1));
                $bgAvatar = str_starts_with($comensal->tipo_comensal ?? '', 'ESTUDIANTE') ? '#0d6efd' : '#198754';
              @endphp
              <div class="rounded-circle text-white fw-bold d-inline-flex align-items-center justify-content-center shadow-sm mb-3"
                   style="width: 90px; height: 90px; font-size: 36px; background-color: {{ $bgAvatar }};">
                {{ $inicial }}
              </div>
            @endif

            <h4 class="fw-bold mb-1 text-dark">{{ $comensal->nombres }} {{ $comensal->apellidos }}</h4>
            <span class="badge bg-secondary-subtle text-primary font-monospace px-3 py-1 fs-6 border">
              C.I. {{ $comensal->nacionalidad }}-{{ number_format($comensal->cedula, 0, '', '.') }}
            </span>
          </div>

          <div class="card border-0 bg-light rounded-3 p-3">
            <div class="row g-3">
              <div class="col-6">
                <small class="text-muted d-block mb-1">Tipo de Comensal</small>
                <span class="badge {{ str_starts_with($comensal->tipo_comensal ?? '', 'ESTUDIANTE') ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-success-subtle text-success border border-success-subtle' }} px-2 py-1">
                  {{ $comensal->tipo_comensal ?? 'NO DEFINIDO' }}
                </span>
              </div>
              <div class="col-6">
                <small class="text-muted d-block mb-1">Estatus en Comedor</small>
                @if($comensal->estatus)
                  <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                    <i class="bi bi-check-circle-fill me-1"></i> ACTIVO
                  </span>
                @else
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                    <i class="bi bi-x-circle-fill me-1"></i> INACTIVO
                  </span>
                @endif
              </div>
              <div class="col-6">
                <small class="text-muted d-block mb-1">Sexo / Género</small>
                <span class="fw-semibold text-dark">
                  @if($comensal->sexo == 'F')
                    <i class="bi bi-gender-female text-danger me-1"></i> FEMENINO
                  @elseif($comensal->sexo == 'M')
                    <i class="bi bi-gender-male text-primary me-1"></i> MASCULINO
                  @else
                    NO ESPECIFICADO
                  @endif
                </span>
              </div>
              <div class="col-6">
                <small class="text-muted d-block mb-1">ID en Sistema</small>
                <span class="fw-semibold text-secondary">#{{ $comensal->id }}</span>
              </div>
              <div class="col-12">
                <small class="text-muted d-block mb-1">Observaciones</small>
                <div class="p-2 bg-white rounded border small text-secondary">
                  {{ $comensal->observacion ?: 'Sin observaciones registradas.' }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div><!-- End Modal Dialog Scrollable-->