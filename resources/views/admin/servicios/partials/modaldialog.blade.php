 <!-- Modal Dialog Scrollable -->
 <a type="button" class="" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable{{$comensal->id}}">
    <i class="bi bi-eye fs-4"></i>
 </a>
  <div class="modal fade" id="modalDialogScrollable{{$comensal->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Informacion del comensal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <section class="section profile">
            <div class="row">

              <div class="col-xl-12">

                <div class="card">
                  <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ asset($comensal->foto) }}" alt="Profile" class="rounded-circle">
                    <h2>  {{ $comensal->nombres }}</h2>
                    <h3>C.I.:{{ $comensal->nacionalidad }}-{{ $comensal->cedula }}  </h3>
                    
                    <h4 >{{ $comensal->tipo }} </h4>

                    <div class="container-fluid">
                      <div class="row">
                        <hr>
                        <div class="col-md-12">
                          <h3>Más información</h3>
                        </div>

                        <div class="col-md-12 label"> 
                          <span class="text-primary">Tipo:</span> {{ $comensal->tipo ?? 'Tipo no definido' }} 
                        </div>
                        <div class="col-md-12 label {{ $comensal->estatus == 0 ? 'bg-danger' : 'bg-success' }}"> 
                          <span class="text-primary">Esatatus:</span> {{ $comensal->estatus == 0 ? 'INACTIVO' : 'ACTIVO' }} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Sexo:</span> {{ $comensal->sexo == "F" ? "FEMENINO" : "MASCULINO" }} 
                        </div>
                        <div class="col-md-12 label"> 
                          <span class="text-primary">Observación:</span> {{ $comensal->observacion ?? 'No hay novedad.' }} 
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