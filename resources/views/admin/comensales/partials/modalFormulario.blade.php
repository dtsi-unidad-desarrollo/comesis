{{-- Boton de agregar comensal --}}
<button type="button" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center" data-bs-toggle="modal"
    data-bs-target="#modalformularioCrearEstudiante">
    <i class="bi bi-person-plus-fill me-2 fs-5"></i>
    <span>Registrar comensal</span>
</button>

<!-- Modal formulario crear estudiante -->
<div class="modal fade text-start" id="modalformularioCrearEstudiante" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="staticBackdropLabel">
                    <i class="bi bi-person-plus-fill me-2 fs-4"></i> Registrar Comensal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('admin.comensales.store') }}" method="POST"
                id="formularioCrearEstudiante" class="needs-validation" enctype="multipart/form-data" novalidate>
                @csrf
                @method('POST')

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- INPUT NOMBRES -->
                        <div class="col-12 col-sm-6">
                            <label for="nombres_crear" class="form-label fw-semibold text-secondary small mb-1">
                                Nombres <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="nombres" class="form-control border-secondary-subtle" id="nombres_crear"
                                    placeholder="Ingrese sus nombres"
                                    value="{{ old('nombres', $request->nombres ?? '') }}" required>
                                <div class="invalid-feedback">Por favor, ingrese los nombres.</div>
                            </div>
                            @error('nombres')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT APELLIDOS -->
                        <div class="col-12 col-sm-6">
                            <label for="apellidos_crear" class="form-label fw-semibold text-secondary small mb-1">
                                Apellidos <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="apellidos" class="form-control border-secondary-subtle" id="apellidos_crear"
                                    placeholder="Ingrese sus apellidos"
                                    value="{{ old('apellidos', $request->apellidos ?? '') }}" required>
                                <div class="invalid-feedback">Por favor, ingrese los apellidos.</div>
                            </div>
                            @error('apellidos')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT NACIONALIDAD -->
                        <div class="col-12 col-sm-4">
                            <label for="nacionalidad_crear" class="form-label fw-semibold text-secondary small mb-1">
                                Nacionalidad <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-flag"></i>
                                </span>
                                <select name="nacionalidad" class="form-select border-secondary-subtle" id="nacionalidad_crear" required>
                                    <option value="">Seleccione</option>
                                    <option value="V" {{ old('nacionalidad', $request->nacionalidad ?? '') == 'V' ? 'selected' : '' }}>Venezolano (V)</option>
                                    <option value="E" {{ old('nacionalidad', $request->nacionalidad ?? '') == 'E' ? 'selected' : '' }}>Extranjero (E)</option>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione la nacionalidad.</div>
                            </div>
                            @error('nacionalidad')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT CEDULA -->
                        <div class="col-12 col-sm-4">
                            <label for="cedula_crear" class="form-label fw-semibold text-secondary small mb-1">
                                Cédula de Identidad <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-card-text"></i>
                                </span>
                                <input type="number" name="cedula" class="form-control border-secondary-subtle" id="cedula_crear"
                                    placeholder="Ej: 24823972"
                                    value="{{ old('cedula', $request->cedula ?? '') }}" required>
                                <div class="invalid-feedback">Por favor, ingrese el número de cédula.</div>
                            </div>
                            @error('cedula')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT SEXO -->
                        <div class="col-12 col-sm-4">
                            <label for="sexo_crear" class="form-label fw-semibold text-secondary small mb-1">
                                Sexo / Género <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-gender-ambiguous"></i>
                                </span>
                                <select name="sexo" class="form-select border-secondary-subtle" id="sexo_crear" required>
                                    <option value="">Seleccione</option>
                                    <option value="F" {{ old('sexo', $request->sexo ?? '') == 'F' ? 'selected' : '' }}>Femenino (F)</option>
                                    <option value="M" {{ old('sexo', $request->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino (M)</option>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione el sexo.</div>
                            </div>
                            @error('sexo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TIPO DE COMENSAL -->
                        <div class="col-12 col-sm-6">
                            <label for="tipo_comensal_crear" class="form-label fw-semibold text-secondary small mb-1">
                                Tipo de Comensal <span class="text-danger">*</span>
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-diagram-3"></i>
                                </span>
                                <select name="tipo_comensal" class="form-select border-secondary-subtle" id="tipo_comensal_crear" required>
                                    <option value="">Seleccione tipo...</option>
                                    <option value="ESTUDIANTE" {{ old('tipo_comensal', $request->tipo_comensal ?? '') == 'ESTUDIANTE' ? 'selected' : '' }}>ESTUDIANTE</option>
                                    <option value="EMPLEADO" {{ old('tipo_comensal', $request->tipo_comensal ?? '') == 'EMPLEADO' ? 'selected' : '' }}>EMPLEADO</option>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione el tipo de comensal.</div>
                            </div>
                            @error('tipo_comensal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT FOTO -->
                        <div class="col-12 col-sm-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="foto_crear" class="form-label fw-semibold text-secondary small mb-0">
                                    Fotografía
                                </label>
                                <span class="badge bg-light text-muted border small">Opcional</span>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-primary">
                                    <i class="bi bi-camera"></i>
                                </span>
                                <input type="file" name="file" class="form-control border-secondary-subtle" id="foto_crear" accept="image/*">
                            </div>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- INPUT OBSERVACIÓN (OPCIONAL) -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="observacion_crear" class="form-label fw-semibold text-secondary small mb-0">
                                    <i class="bi bi-chat-left-text text-primary me-1"></i> Observación
                                </label>
                                <span class="badge bg-light text-muted border small">Opcional</span>
                            </div>
                            <textarea class="form-control border-secondary-subtle" id="observacion_crear" name="observacion"
                                rows="3" maxlength="500"
                                placeholder="Escriba aquí cualquier observación médica, condición especial o nota relevante sobre el comensal...">{{ old('observacion', $request->observacion ?? '') }}</textarea>
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
                        <i class="bi bi-check-circle-fill me-1"></i> Guardar Comensal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Cierre Modal formulario crear estudiante -->
