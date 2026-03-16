       {{-- Boton de agregar estudiante --}}
       <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporte">
           <i class="bi bi-file-bar-graph"></i>
           Crear Reporte
       </button>

       <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporteSemanal">
           <i class="bi bi-calendar-week"></i> Reporte Semanal
       </button>

       <!-- Modal formulario crear reporte -->
       <div class="modal fade text-start" id="modalformularioCrearReporte" data-bs-backdrop="static"
           data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
           <div class="modal-dialog  modal-xl">
               <div class="modal-content">
                   <div class="modal-header bg-primary text-white">
                       <h5 class="modal-title" id="staticBackdropLabel">Generar reporte</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                   </div>
                   <div class="modal-body">
                       <form action="{{ route('admin.entradas.reporte') }}" method="POST" target="_blank" id="formularioCrearReporte"
                           class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
                           @csrf
                           @method('POST')




                           <div class="col-6">
                               <label for="servicio" class="form-label">Servicio</label>
                               <select name="servicio" id="servicio" class="form-select">
                                   <option value="0">Todos los servicios</option>
                                   @foreach ($servicios as $servicio)
                                   <option value="{{ $servicio->nombre }}">{{ $servicio->nombre }}</option>
                                   @endforeach
                               </select>
                           </div>

                           <div class="col-6">
                               <label for="tipo" class="form-label">Tipo de comensal</label>
                               <select name="tipo" id="tipo" class="form-select">
                                   <option value="TODOS">TODOS</option>
                                   @foreach ($tipos as $tipo)
                                   <option value="{{ $tipo }}">{{ $tipo }}</option>
                                   @endforeach
                               </select>
                           </div>

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


       <div class="modal fade text-start" id="modalformularioCrearReporteSemanal" data-bs-backdrop="static"
           data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
           <div class="modal-dialog  modal-xl">
               <div class="modal-content">
                   <div class="modal-header bg-primary text-white">
                       <h5 class="modal-title" id="staticBackdropLabel">Generar reporte semanal</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                   </div>
                   <div class="modal-body">
                       <form action="{{ route('admin.entradas.reporte.semanal') }}" method="POST" target="_blank" id="modalformularioCrearReporteSemanal"
                           class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>
                           @csrf
                           @method('POST')




                          <div class="col-6">
                               <label for="tipo" class="form-label">Fecha de inicio</label>
                             <input type="date" class="form-control" name="fecha_inicio" aria-label="fecha_inicio"
                               aria-describedby="button-addon2">

                           </div>

                           

                           
                           <div class="col-6">
                               <label for="tipo" class="form-label">Fecha de fin</label>
                             <input type="date" class="form-control" name="fecha_fin" aria-label="fecha_fin"
                               aria-describedby="button-addon2">

                           </div>



                           <div class="col-12">
                               <button class="btn btn-primary w-100" type="submit">Generar Reporte Semanal</button>
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