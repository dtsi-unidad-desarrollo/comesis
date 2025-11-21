 <!-- Modal Dialog Scrollable -->
 <a type="button" class="" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$entrada->id}}">
    <i class="bi bi-eye fs-4"></i>
 </a>
  <div class="modal fade" id="modalDialogScrollable{{$entrada->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Informacion del entrada</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <section class="section profile">
            <div class="row">

              <div class="col-xl-12">

                <div class="card">
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                  
                    <h2>  
                      {{ $entrada->nombres }}
                      {{ $entrada->apellidos }}

                    </h2>
                    <h3>C.I.:{{ $entrada->nacionalidad }}-{{ $entrada->cedula }}  </h3>
                    <h3>Genero:{{ $entrada->sexo }} </h3>
                    
                    <h3 >{{ $entrada->tipo_comensal }} </h3>

                    <div class="container-fluid">
                      <div class="row">
                        <hr>
                        <div class="col-md-12">
                          <h3>Más información</h3>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Servicio consumido:</span> {{ $entrada->comida ?? ''}} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">fecha y hora:</span> {{ $entrada->fecha . " " . $entrada->hora ?? ''}} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Carrera:</span> {{ $entrada->carrera ?? 'No poseé carrera' }} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Sede:</span> {{ $entrada->sede ?? 'Sin sede'}} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Estado:</span> {{ $entrada->estado ?? 'Sin estado'}} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Municipio:</span> {{ $entrada->municipio ?? 'Sin municipio'}} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Dirección:</span> {{ $entrada->direccion ?? 'Sin direccion'}} 
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
      
              </div>
            </div>
          </section>
          
          
            
          


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div><!-- End Modal Dialog Scrollable-->