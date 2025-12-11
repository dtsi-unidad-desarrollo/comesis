       {{-- Boton de agregar estudiante --}}
       <a type="button" class="text-warning" data-bs-toggle="modal"
           data-bs-target="#modalformularioEditarComensal{{ $comensal->id }}">
           <i class="bi bi-pencil fs-4"></i>
       </a>

       <!-- Modal formulario crear estudiante -->
       <div class="modal fade text-start" id="modalformularioEditarComensal{{ $comensal->id }}" data-bs-backdrop="static"
           data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
           <div class="modal-dialog  modal-xl">
               <div class="modal-content">
                   <div class="modal-header bg-primary text-white">
                       <h5 class="modal-title" id="staticBackdropLabel">Actualizar datos del comensal </h5>
                       <button type="button" class="btn-danger" data-bs-dismiss="modal" aria-label="Cerrra"></button>
                   </div>
                   <div class="modal-body">
                       <form action="{{ route('admin.comensales.update', $comensal->id) }}" method="POST"
                           id="formularioCrearEstudiante" class="row g-3 needs-validation" enctype="multipart/form-data"
                           novalidate>
                           {{--  --}}
                           @csrf
                           @method('PUT')

                           <!--Elemento foto actual-->
                           <div class="col-12 text-center">
                               @if ($comensal->foto)
                                   <img src="{{ asset($comensal->foto) }}" alt="foto">
                               @else
                                   <div class="alert alert-dark" role="alert">
                                       NO POSEÉ IMAGEN
                                   </div>
                               @endif
                           </div><!--CIERRE Elemento foto actual-->

                           {{-- INICIO DE DATOS PERSONALES --}}

                           <!-- Input Nombres -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="yourUsername" class="form-label">Nombres</label>
                               <div class="input-group has-validation">
                                   <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                       <i class="bi bi-people"></i>
                                   </span>
                                   <input type="text" name="nombres" class="form-control" id="yourUsername"
                                       placeholder="Ingrese nombre completo"
                                       value="{{ old('nombres') ?? $comensal->nombres }}" required>
                                   <div class="invalid-feedback">Por favor, ingrese nombre! </div>
                                   @error('nombres')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div> <!-- Cierre Input Nombres -->

                           <!-- Input Apellidos -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="yourUsername" class="form-label">Apellidos</label>
                               <div class="input-group has-validation">
                                   <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                       <i class="bi bi-people"></i>
                                   </span>
                                   <input type="text" name="apellidos" class="form-control" id="yourUsername"
                                       placeholder="Ingrese apellidos"
                                       value="{{ old('apellidos') ?? $comensal->apellidos }}" required>
                                   <div class="invalid-feedback">Por favor, ingrese apellidos! </div>
                                   @error('apellidos')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div> <!-- Cierre Input Apellidos -->

                           <!-- Input Nacionalidad -->
                           <div class="col-xs-12 col-sm-4">
                               <label for="validationCustom04" class="form-label">Nacionalidad</label>
                               <select name="nacionalidad" class="form-select" id="validationCustom04" required>
                                   @if (old('nacionalidad'))
                                       <option value="{{ old('nacionalidad') }}" selected>
                                           {{ old('nacionalidad') }}
                                       </option>
                                   @endif
                                   @if ($comensal->nacionalidad)
                                       <option value="{{ $comensal->nacionalidad }}" selected>
                                           {{ old('nacionalidad') ?? $comensal->nacionalidad }}
                                       </option>
                                   @endif


                                   <option value="">Seleccione Nacionalidad</option>
                                   <option value="V">V</option>
                                   <option value="E">E</option>
                               </select>
                               <div class="invalid-feedback">
                                   Por favor, ingresar nacionalidad!
                               </div>
                               @error('nacionalidad')
                                   <div class="text-danger">{{ $message }}</div>
                               @enderror
                           </div> <!-- Cierre Input Nacionalidad -->

                           <!-- Input Cedula -->
                           <div class="col-xs-12 col-sm-4">
                               <label for="yourPassword" class="form-label">Cédula</label>
                               <div class="input-group">
                                   <input type="text" name="cedula" class="form-control bg-muted inputCedula"
                                       id="{{ $comensal->cedula }}" placeholder="Ingrese cédula"
                                       value="{{ old('cedula') ?? $comensal->cedula }}" disabled readonly required>
                                   <button type="button" class="btn btn-warning activarEdicionDeCedula"
                                       id="activarEdicionDeCedula">
                                       <i class="bi bi-pencil"></i>
                                   </button>
                               </div>
                               <div class="invalid-feedback">Por favor, Ingrese número de cédula!</div>
                               @error('cedula')
                                   <div class="text-danger"> {{ $message }} </div>
                               @enderror
                               
                               <div class="form-check form-switch">
                                   <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                       value="1" name="estatus" {{ $comensal->estatus ? 'checked' : '' }}>
                                   <label class="form-check-label"
                                       for="flexSwitchCheckChecked">{{ $comensal->estatus ? 'ACTIVO' : 'INACTIVO' }}</label>
                               </div>
                           </div> <!-- Cierre Input Cedula -->

                           <!-- INPUT SEXO -->
                           <div class="col-xs-12 col-sm-4">
                               <label for="validationCustom04" class="form-label">Sexo</label>
                               <select name="sexo" class="form-select" id="validationCustom04" required>
                                   @if (old('sexo'))
                                       <option value="{{ old('sexo') }}" selected>
                                           {{ old('sexo') }}
                                       </option>
                                   @endif
                                   @if ($comensal->sexo)
                                       <option value="{{ $comensal->sexo }}" selected>
                                           {{ $comensal->sexo }}
                                       </option>
                                   @endif

                                   <option value="">Seleccione sexo</option>
                                   <option value="F">F</option>
                                   <option value="M">M</option>
                               </select>
                               <div class="invalid-feedback">
                                   Por favor, ingresar sexo!
                               </div>
                               @error('sexo')
                                   <div class="text-danger">{{ $message }}</div>
                               @enderror
                           </div><!-- CIERRE DE INPUT SEXO -->

                           <!-- Tipo de comensal -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="validationCustom04" class="form-label">Tipo de comensal</label>
                               <select name="tipo_comensal" class="form-select" id="validationCustom04" required>
                                   @if (old('tipo_comensal'))
                                       <option value="{{ old('tipo_comensal') }}" selected>
                                           {{ old('tipo_comensal') }}
                                       </option>
                                   @else
                                       <option value="{{ $comensal->tipo_comensal }}" selected>
                                           {{ $comensal->tipo_comensal }}
                                       </option>
                                   @endif


                                   <option value="" disabled>Seleccione tipo</option>
                                   <option value="ESTUDIANTE FORANEO">ESTUDIANTE FORANEO</option>
                                   <option value="PROFESOR">PROFESOR</option>
                                   <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                   <option value="OBRERO">OBRERO</option>
                                   <option value="EVENTUAL">EVENTUAL</option>
                                   <option value="EMPLEADO">EMPLEADO</option>
                               </select>
                               <div class="invalid-feedback">
                                   Por favor, ingresar tipo del comensal!
                               </div>
                               @error('tipo')
                                   <div class="text-danger">{{ $message }}</div>
                               @enderror
                           </div> <!-- Cierre de Tipo de comensal -->

                           <!-- Input foto -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="foto" class="form-label">Subir Foto (Opcional)</label>
                               <input type="file" name="file" class="form-control " id="file"
                                   accept="image/*">
                               @error('file')
                                   <div class="text-danger">{{ $message }}</div>
                               @enderror
                           </div><!-- Cierre Input foto -->

                           <!-- Input Observacion del evento -->
                           <div class="col-xs-12 col-sm-12">
                               <label for="yourPassword" class="form-label">Observación</label>

                               <div class="input-group">
                                   <span class="input-group-text">Agregue una observación del comensal</span>
                                   <textarea class="form-control" aria-label="With textarea" name="observacion" minlength="15" maxlength="500">
                                     {{ old('observacion') ?? $comensal->observacion }}
                                 </textarea>
                               </div>

                               <div class="invalid-feedback">Por favor, Ingrese observación descriptiva!</div>
                               @error('observacion')
                                   <div class="text-danger"> {{ $message }} </div>
                               @enderror
                           </div><!-- Cierre Input Observacion del evento -->


                           <div class="col-12">
                               <button class="btn btn-primary w-100" type="submit">Guardar datos</button>
                           </div>


                       </form>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                       {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
                   </div>
               </div>
           </div>
       </div>
       <!-- Cierre Modal formulario crear comensal -->
