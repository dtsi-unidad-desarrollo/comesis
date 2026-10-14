{{-- Boton de editar comensal --}}
<button type="button" class="btn btn-sm btn-outline-warning rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" data-bs-toggle="modal"
    data-bs-target="#modalformularioEditarComensal{{ $comensal->id }}" title="Editar comensal">
    <i class="bi bi-pencil"></i>
</button>

<!-- Modal formulario editar comensal -->
<div class="modal fade text-start" id="modalformularioEditarComensal{{ $comensal->id }}" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel{{ $comensal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="staticBackdropLabel{{ $comensal->id }}">
                    <i class="bi bi-pencil-square me-2 fs-4"></i> Actualizar Datos del Comensal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('admin.comensales.update', $comensal->id) }}" method="POST"
                id="formularioEditarComensal{{ $comensal->id }}" class="needs-validation" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="modal-body p-4">
                    {{-- Elemento foto actual y Estatus --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between p-3 bg-light rounded-4 mb-3 border">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                @if ($comensal->foto && file_exists(public_path($comensal->foto)))
                                    <img src="{{ asset($comensal->foto) }}" alt="Foto"
                                         class="rounded-circle border border-2 border-primary shadow-sm"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @elseif ($comensal->foto && str_starts_with($comensal->foto, 'http'))
                                    <img src="{{ $comensal->foto }}" alt="Foto"
                                         class="rounded-circle border border-2 border-primary shadow-sm"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary border border-2 border-primary d-flex align-items-center justify-content-center shadow-sm"
                                         style="width: 60px; height: 60px; font-size: 24px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $comensal->nombres }} {{ $comensal->apellidos }}</h6>
                                <span class="text-muted small">Cédula: <strong>{{ $comensal->nacionalidad }}-{{ $comensal->cedula }}</strong></span>
                            </div>
                        </div>

                        <div class="form-check form-switch mt-2 mt-sm-0">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked{{ $comensal->id }}"
                                value="1" name="estatus" {{ $comensal->estatus ? 'checked' : '' }} style="cursor: pointer;">
                            <label class="form-check-label fw-semibold small {{ $comensal->estatus ? 'text-success' : 'text-danger' }}"
                                for="flexSwitchCheckChecked{{ $comensal->id }}">
                                {{ $comensal->estatus ? 'ACTIVO' : 'INACTIVO' }}
                            </label>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Input Nombres -->
                        <div class="col-12 col-sm-6">
                            <label for="nombres_edit_{{ $comensal->id }}" class="form-label fw-semibold text-secondary small mb-1">
                                Nombres <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="nombres" class="form-control border-secondary-subtle" id="nombres_edit_{{ $comensal->id }}"
                                    placeholder="Ingrese nombres"
                                    value="{{ old('nombres', $comensal->nombres) }}" required>
                                <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                            </div>
                            @error('nombres')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Apellidos -->
                        <div class="col-12 col-sm-6">
                            <label for="apellidos_edit_{{ $comensal->id }}" class="form-label fw-semibold text-secondary small mb-1">
                                Apellidos <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="apellidos" class="form-control border-secondary-subtle" id="apellidos_edit_{{ $comensal->id }}"
                                    placeholder="Ingrese apellidos"
                                    value="{{ old('apellidos', $comensal->apellidos) }}" required>
                                <div class="invalid-feedback">Por favor, ingrese los apellidos.</div>
                            </div>
                            @error('apellidos')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Nacionalidad -->
                        <div class="col-12 col-sm-4">
                            <label for="nacionalidad_edit_{{ $comensal->id }}" class="form-label fw-semibold text-secondary small mb-1">
                                Nacionalidad <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-flag"></i>
                                </span>
                                <select name="nacionalidad" class="form-select border-secondary-subtle" id="nacionalidad_edit_{{ $comensal->id }}" required>
                                    <option value="">Seleccione</option>
                                    <option value="V" {{ old('nacionalidad', $comensal->nacionalidad) == 'V' ? 'selected' : '' }}>Venezolano (V)</option>
                                    <option value="E" {{ old('nacionalidad', $comensal->nacionalidad) == 'E' ? 'selected' : '' }}>Extranjero (E)</option>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione la nacionalidad.</div>
                            </div>
                            @error('nacionalidad')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Cédula con Desbloqueo -->
                        <div class="col-12 col-sm-4">
                            <label for="cedula_edit_{{ $comensal->id }}" class="form-label fw-semibold text-secondary small mb-1">
                                Cédula de Identidad <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="cedula" class="form-control border-secondary-subtle bg-light inputCedula"
                                    id="{{ $comensal->cedula }}" placeholder="Ingrese cédula"
                                    value="{{ old('cedula', $comensal->cedula) }}" disabled readonly required>
                                <button type="button" class="btn btn-warning activarEdicionDeCedula"
                                    id="activarEdicionDeCedula" title="Activar/Bloquear edición de cédula">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Por favor, ingrese número de cédula.</div>
                            @error('cedula')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT SEXO -->
                        <div class="col-12 col-sm-4">
                            <label for="sexo_edit_{{ $comensal->id }}" class="form-label fw-semibold text-secondary small mb-1">
                                Sexo / Género <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-gender-ambiguous"></i>
                                </span>
                                <select name="sexo" class="form-select border-secondary-subtle" id="sexo_edit_{{ $comensal->id }}" required>
                                    <option value="">Seleccione</option>
                                    <option value="F" {{ old('sexo', $comensal->sexo) == 'F' ? 'selected' : '' }}>Femenino (F)</option>
                                    <option value="M" {{ old('sexo', $comensal->sexo) == 'M' ? 'selected' : '' }}>Masculino (M)</option>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione el sexo.</div>
                            </div>
                            @error('sexo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipo de comensal -->
                        <div class="col-12 col-sm-6">
                            <label for="tipo_comensal_edit_{{ $comensal->id }}" class="form-label fw-semibold text-secondary small mb-1">
                                Tipo de Comensal <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-diagram-3"></i>
                                </span>
                                <select name="tipo_comensal" class="form-select border-secondary-subtle" id="tipo_comensal_edit_{{ $comensal->id }}" required>
                                    <option value="" disabled>Seleccione tipo...</option>
                                    @php
                                        $currentTipo = old('tipo_comensal', $comensal->tipo_comensal);
                                    @endphp
                                    @if(!in_array($currentTipo, ['ESTUDIANTE', 'EMPLEADO']) && !empty($currentTipo))
                                        <option value="{{ $currentTipo }}" selected>{{ $currentTipo }} (Actual)</option>
                                    @endif
                                    <option value="ESTUDIANTE" {{ $currentTipo == 'ESTUDIANTE' ? 'selected' : '' }}>ESTUDIANTE</option>
                                    <option value="EMPLEADO" {{ $currentTipo == 'EMPLEADO' ? 'selected' : '' }}>EMPLEADO</option>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione el tipo de comensal.</div>
                            </div>
                            @error('tipo_comensal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input foto -->
                        <div class="col-12 col-sm-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="foto_edit_{{ $comensal->id }}" class="form-label fw-semibold text-secondary small mb-0">
                                    Cambiar Foto
                                </label>
                                <span class="badge bg-light text-muted border small">Opcional</span>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-camera"></i>
                                </span>
                                <input type="file" name="file" class="form-control border-secondary-subtle" id="foto_edit_{{ $comensal->id }}" accept="image/*">
                            </div>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Observacion (Opcional) -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="observacion_edit_{{ $comensal->id }}" class="form-label fw-semibold text-secondary small mb-0">
                                    <i class="bi bi-chat-left-text text-primary me-1"></i> Observación
                                </label>
                                <span class="badge bg-light text-muted border small">Opcional</span>
                            </div>
                            <textarea class="form-control border-secondary-subtle" id="observacion_edit_{{ $comensal->id }}" name="observacion"
                                rows="3" maxlength="500"
                                placeholder="Escriba aquí cualquier observación médica, condición especial o nota relevante sobre el comensal...">{{ old('observacion', $comensal->observacion) }}</textarea>
                            <div class="form-text text-muted small">
                                <i class="bi bi-info-circle me-1"></i> Campo opcional. Máximo 500 caracteres.
                            </div>
                            @error('observacion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" type="submit">
                        <i class="bi bi-check-circle-fill me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Cierre Modal formulario editar comensal -->
