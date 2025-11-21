       {{-- Boton de agregar estudiante --}}
       <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalServiciosNuevo">
           <i class="bi bi-cup"></i>
           Agregar nuevo servicio
       </button>

       <!-- Modal formulario crear estudiante -->
       <div class="modal fade text-start" id="ModalServiciosNuevo" data-bs-backdrop="static" data-bs-keyboard="false"
           tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
           <div class="modal-dialog  modal-xl">
               <div class="modal-content">
                   <div class="modal-header bg-primary text-white">
                       <h5 class="modal-title" id="staticBackdropLabel">Agregar nuevo servicio</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrra"></button>
                   </div>
                   <div class="modal-body">
                       <form action="{{ route('admin.servicios.store') }}" method="POST" id="formularioCrearEstudiante"
                           class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>

                           @csrf
                           @method('POST')

                           <!-- Input Nombre del servicio -->
                           <div class="col-xs-12 col-sm-12">
                               <label for="yourUsername" class="form-label">Nombre del servicio</label>
                               <div class="input-group has-validation">
                                   <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                       <i class="bi bi-cup"></i>
                                   </span>
                                   <input type="text" name="nombre" class="form-control" id="yourUsername"
                                       placeholder="Ingrese nombre del servicio"
                                       value="{{ old('nombre') }}" required>
                                   <div class="invalid-feedback">Por favor, ingrese nombre! </div>
                                   @error('nombre')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div> <!-- Cierre Input Nombre del servicio -->

                           <!-- Input hora de inicio del servicio -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="yourUsername" class="form-label">Hora inicio</label>
                               <div class="input-group has-validation">
                                   <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                       <i class="bi bi-alarm"></i>
                                   </span>
                                   <input type="time" name="hora_inicio" class="form-control" id="hora_inicio"
                                       value="{{ old('hora_inicio') }}" required>
                                   <div class="invalid-feedback">Por favor, ingrese hora de apertura! </div>
                                   @error('hora_inicio')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div> <!-- Cierre Input hora de inicio del servicio -->

                           <!-- Input hora de cierre del servicio -->
                           <div class="col-xs-12 col-sm-6">
                               <label for="yourUsername" class="form-label">Hora cierre</label>
                               <div class="input-group has-validation">
                                   <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                       <i class="bi bi-alarm"></i>
                                   </span>
                                   <input type="time" name="hora_cierre" class="form-control" id="yourUsername"
                                       value="{{ old('hora_cierre') }}" required>
                                   <div class="invalid-feedback">Por favor, ingrese hora de cierre! </div>
                                   @error('hora_cierre')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div> <!-- Cierre Input hora de cierre del servicio -->

                           <!-- Input DISPONIBILIDAD del servicio -->
                           <div class="col-12">
                               <label for="yourUsername" class="form-label">Disponibilidad</label>
                               <div class="input-group has-validation">
                                    <span class="input-group-text text-white bg-primary" id="inputGroupPrepend">
                                        <i class="bi bi-box"></i>
                                    </span>
                                   <input type="number" name="disponibilidad" class="form-control" 
                                        min="0" max="5000" step="1" id="disponibilidad"
                                        placeholder="Ingrese la cantidad disponible de comidas del servicio"
                                        value="{{ old('disponibilidad') }}" required>

                                   <div class="invalid-feedback">Por favor, ingrese la disponibilidad del servicio!
                                   </div>
                                   @error('disponibilidad')
                                       <div class="text-danger">{{ $message }}</div>
                                   @enderror
                               </div>
                           </div> <!-- Cierre Input DISPONIBILIDAD del servicio -->

                           <div class="col-12">
                               <button class="btn btn-primary w-100" type="submit">Registrar servicio</button>
                           </div>

                       </form>

                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                   </div>
               </div>
           </div>
       </div>
       <!-- Cierre Modal formulario crear estudiante -->
