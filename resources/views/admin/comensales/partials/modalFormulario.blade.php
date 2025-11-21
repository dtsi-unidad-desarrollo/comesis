       {{-- Boton de agregar estudiante --}}
       <button type="button" class="btn btn-primary" data-bs-toggle="modal"
           data-bs-target="#modalformularioCrearEstudiante">
           <i class="bi bi-people"></i>
           Registrar comensal
       </button>

       <!-- Modal formulario crear estudiante -->
       <div class="modal fade text-start" id="modalformularioCrearEstudiante" data-bs-backdrop="static"
           data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
           <div class="modal-dialog  modal-xl">
               <div class="modal-content">
                   <div class="modal-header bg-primary text-white">
                       <h5 class="modal-title" id="staticBackdropLabel">Registrar comensal</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrra"></button>
                   </div>
                   <div class="modal-body">
                       <form action="{{ route('admin.comensales.store') }}" method="POST"
                           id="formularioCrearEstudiante" class="row g-3 needs-validation" enctype="multipart/form-data"
                           novalidate>

                           @csrf
                           @method('POST')


                           <!-- INPUT NOMBRES -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="yourUsername" class="form-label">Nombres</label>
                               <div class="input-group has-validation">
                                   <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                       <i class="bi bi-people"></i>
                                   </span>
                                   <input type="text" name="nombres" class="form-control" id="yourUsername"
                                       placeholder="Ingrese sus nombres"
                                       value="{{ $request->nombres ?? old('nombres') }}" required>
                                   <div class="invalid-feedback">Por favor, ingrese nombre! </div>
                                   @error('nombres')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div><!-- CIERRE DE INPUT NOMBRES -->

                           <!-- INPUT APELLIDOS -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="yourUsername" class="form-label">Apellidos</label>
                               <div class="input-group has-validation">
                                   <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                       <i class="bi bi-people"></i>
                                   </span>
                                   <input type="text" name="apellidos" class="form-control" id="yourUsername"
                                       placeholder="Ingrese sus apellidos"
                                       value="{{ $request->apellidos ?? old('apellidos') }}" required>
                                   <div class="invalid-feedback">Por favor, ingrese apellidos! </div>
                                   @error('apellidos')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div><!-- CIERRE INPUT APELLIDOS -->

                           <!-- INPUT NACIONALIDAD -->
                           <div class="col-xs-12 col-sm-4">
                               <label for="validationCustom04" class="form-label">Nacionalidad</label>
                               <select name="nacionalidad" class="form-select" id="validationCustom04" required>
                                   @if (old('nacionalidad'))
                                       <option value="{{ old('nacionalidad') }}" selected>
                                           {{ old('nacionalidad') }}
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
                           </div><!-- CIERRE DE INPUT NACIONALIDAD -->

                           <!-- INPUT CEDULA -->
                           <div class="col-xs-12 col-sm-4">
                               <label for="yourPassword" class="form-label">Cédula</label>
                               <input type="number" name="cedula" class="form-control" id="yourUsername"
                                   placeholder="Ingrese número de cédula"
                                   value="{{ $request->cedula ?? old('cedula') }}" required>
                               <div class="invalid-feedback">Por favor, Ingrese número de cédula!</div>
                               @error('cedula')
                                   <div class="text-danger"> {{ $message }} </div>
                               @enderror
                           </div><!-- CIERRE DE INPUT CEDULA -->

                           <!-- INPUT SEXO -->
                           <div class="col-xs-12 col-sm-4">
                               <label for="validationCustom04" class="form-label">Sexo</label>
                               <select name="sexo" class="form-select" id="validationCustom04" required>
                                   @if (old('sexo'))
                                       <option value="{{ old('sexo') }}" selected>
                                           {{ old('sexo') }}
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

                             <!-- TIPO DE COMENSAL -->
                             <div class="col-xs-12 col-sm-6">
                                <label for="validationCustom04" class="form-label">Tipo de comensal</label>
                                <select name="tipo_comensal" class="form-select" id="validationCustom04" required>
                                    @if (old('tipo_comensal'))
                                        <option value="{{ old('tipo_comensal') }}" selected>
                                            {{ old('tipo_comensal') }}
                                        </option>
                                    @endif
 
                                    <option value="">Seleccione tipo</option>
                                    <option value="ESTUDIANTE FORANEO">ESTUDIANTE FORANEO</option>
                                    <option value="PROFESOR">PROFESOR</option>
                                    <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                    <option value="OBRERO">OBRERO</option>
                                    <option value="EVENTUAL">EVENTUAL</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor, ingresar tipo del comensal!
                                </div>
                                @error('tipo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> <!-- CIERRE DE TIPO DE COMENSAL -->

                           <!-- INPUT FOTO -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="foto" class="form-label">Subir Foto (Opcional)</label>
                               <input type="file" name="file" class="form-control " id="file"
                                   accept="image/*">
                               @error('file')
                                   <div class="text-danger">{{ $message }}</div>
                               @enderror
                           </div><!-- CIERRE DE INPUT FOTO -->

                            <!-- INPUT OBSERVACIÓN -->
                            <div class="col-12">
                                <label for="yourPassword" class="form-label">Observación</label>
 
                                <div class="input-group">
                                    <span class="input-group-text">Agregue una observación del comensal</span>
                                    <textarea class="form-control" aria-label="With textarea" name="observacion" minlength="15" maxlength="500">
                                              {{ old('observacion') ?? '' }}
                                     </textarea>
                                </div>
 
                                <div class="invalid-feedback">Por favor, Ingrese observación descriptiva!</div>
                                @error('observacion')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div><!-- CIERRE INPUT OBSERVACIÓN -->








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
       <!-- Cierre Modal formulario crear estudiante -->
