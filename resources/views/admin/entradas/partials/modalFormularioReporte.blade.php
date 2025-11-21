       {{-- Boton de agregar estudiante --}}
       <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporte">
           <i class="bi bi-file-bar-graph"></i>
           Crear Reporte
       </button>

       <!-- Modal formulario crear estudiante -->
       <div class="modal fade text-start" id="modalformularioCrearReporte" data-bs-backdrop="static"
           data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
           <div class="modal-dialog  modal-xl">
               <div class="modal-content">
                   <div class="modal-header bg-primary text-white">
                       <h5 class="modal-title" id="staticBackdropLabel">Generar reporte</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrra"></button>
                   </div>
                   <div class="modal-body">
                       <form action="{{ route('admin.entradas.reporte') }}" method="POST"  target="_blank" id="formularioCrearReporte"
                           class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
                           @csrf
                           @method('POST')


                           <select name="servicio" id="servicio" class="form-select">
                               <option value="0">Seleccione servicio</option>
                               @foreach ($servicios as $servicio)
                                   <option value="{{ $servicio->nombre }}">{{ $servicio->nombre }}</option>
                               @endforeach
                           </select>

                           <select name="servicio" id="servicio" class="form-select">
                               <option value="0">Seleccione servicio</option>
                               @foreach ($tipos as $tipo)
                                   <option value="{{ $tipo }}">{{ $tipo }}</option>
                               @endforeach
                           </select>

                           <input type="date" class="form-control" name="fecha" aria-label="fecha"
                               aria-describedby="button-addon2">



                           <div class="col-12">
                               <button class="btn btn-primary w-100" type="submit">Generar Reporte</button>
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
