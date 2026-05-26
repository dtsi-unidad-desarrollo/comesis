       {{-- Boton de agregar estudiante --}}
       <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporte">
           <i class="bi bi-file-bar-graph"></i>
           Crear Reporte
       </button>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporteSemanal">
            <i class="bi bi-calendar-week"></i> Reporte Semanal
        </button>

        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporteMensual">
            <i class="bi bi-calendar-month"></i> Reporte Mensual
        </button>

        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporteSemanasMes">
            <i class="bi bi-calendar3"></i> Reporte Semanas del Mes
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
                                   <!-- <option value="0">Todos los servicios</option> -->
                                   <!-- <option value="ALMUERZO" selected>ALMUERZO</option> -->
                                   @foreach ($servicios as $servicio)
                                   @if ($servicio->nombre == 'ALMUERZO')
                                       <option value="{{ $servicio->nombre }}" selected>{{ $servicio->nombre }}</option>
                                   @endif
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


 <!-- modal semanal -->
 <div class="modal fade text-start" id="modalformularioCrearReporteSemanal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalReporteSemanalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header bg-success text-white">
         <h5 class="modal-title" id="modalReporteSemanalLabel">Generar Reporte Semanal</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
       </div>
       <div class="modal-body">
         <form action="{{ route('admin.entradas.reporte.semanal') }}" method="POST" target="_blank" id="formularioReporteSemanal">
           @csrf
           <div class="row g-3">
             <div class="col-md-4">
               <label class="form-label">Año</label>
               <select name="anio" id="anioSemanal" class="form-select" required>
                 @php
                     $currentYear = date('Y');
                     for ($y = $currentYear; $y >= $currentYear - 5; $y--):
                 @endphp
                     <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                 @endfor
                 @endphp
               </select>
             </div>
              <div class="col-md-4">
                <label class="form-label">Mes</label>
                <select name="mes" id="mesSemanal" class="form-select" required>
                  @php
                      $meses = [
                          1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                          5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                          9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                      ];
                      $currentMonth = date('n');
                  @endphp
                  @foreach($meses as $num => $nombre)
                      @if($num <= $currentMonth)
                          <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>{{ $nombre }}</option>
                      @endif
                  @endforeach
                </select>
              </div>
             <div class="col-md-4">
               <label class="form-label">Semana</label>
               <select name="semana" id="semanaSelect" class="form-select" required>
                 <!-- Las semanas se cargarán dinámicamente con JavaScript -->
               </select>
             </div>
             <div class="col-md-6">
               <label class="form-label">Servicio</label>
               <select name="servicio" class="form-select">
                 <!-- <option value="0">Todos</option> -->
                 @foreach ($servicios as $servicio)
                 @if ($servicio->nombre == 'ALMUERZO')
                     <option value="{{ $servicio->nombre }}" selected>{{ $servicio->nombre }}</option>
                  @endif
                   <option value="{{ $servicio->nombre }}">{{ $servicio->nombre }}</option>
                 @endforeach
               </select>
             </div>
             <div class="col-md-6">
               <label class="form-label">Tipo</label>
               <select name="tipo" class="form-select">
                 <option value="TODOS">TODOS</option>
                 @foreach ($tipos as $tipo)
                   <option value="{{ $tipo }}">{{ $tipo }}</option>
                 @endforeach
               </select>
             </div>
             <div class="col-12">
               <button class="btn btn-success w-100" type="submit">Generar Reporte Semanal</button>
             </div>
           </div>
         </form>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
       </div>
     </div>
   </div>
 </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const anioSelect = document.getElementById('anioSemanal');
        const mesSelect = document.getElementById('mesSemanal');
        const semanaSelect = document.getElementById('semanaSelect');
        
        function actualizarMesesDisponibles() {
            const anioSeleccionado = parseInt(anioSelect.value);
            const anioActual = new Date().getFullYear();
            const mesActual = new Date().getMonth() + 1;
            
            const options = mesSelect.querySelectorAll('option');
            options.forEach(option => {
                const mes = parseInt(option.value);
                let habilitado = true;
                
                if (anioSeleccionado === anioActual && mes > mesActual) {
                    habilitado = false;
                }
                
                option.disabled = !habilitado;
                if (option.selected && !habilitado) {
                    option.selected = false;
                }
            });
            
            if (!mesSelect.value) {
                for (let option of options) {
                    if (!option.disabled) {
                        option.selected = true;
                        break;
                    }
                }
            }
            
            calcularSemanasDisponibles();
        }
        
        function calcularSemanasDisponibles() {
            const anio = parseInt(anioSelect.value);
            const mes = parseInt(mesSelect.value);
            
            if (!anio || !mes) return;
            
            semanaSelect.innerHTML = '';
            
            const primerDiaMes = new Date(anio, mes - 1, 1);
            const ultimoDiaMes = new Date(anio, mes, 0);
            
            // Encontrar el primer lunes del mes
            let lunes = new Date(primerDiaMes);
            while (lunes.getDay() !== 1) {
                lunes.setDate(lunes.getDate() + 1);
            }
            
            // Si no hay lunes en el mes (todos los lunes son del mes siguiente)
            if (lunes.getMonth() !== mes - 1) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay semanas disponibles';
                option.disabled = true;
                semanaSelect.appendChild(option);
                return;
            }
            
            let semanaNum = 1;
            const hoy = new Date();
            const esMesActual = hoy.getFullYear() === anio && (hoy.getMonth() + 1) === mes;
            
            while (lunes.getMonth() === mes - 1) {
                const sabado = new Date(lunes);
                sabado.setDate(lunes.getDate() + 5);
                
                const lunesStr = String(lunes.getDate()).padStart(2, '0') + '/' + String(lunes.getMonth() + 1).padStart(2, '0');
                const sabadoStr = String(sabado.getDate()).padStart(2, '0') + '/' + String(sabado.getMonth() + 1).padStart(2, '0');
                
                // En mes actual, solo mostrar semanas cuyo lunes sea <= hoy
                if (esMesActual && lunes > hoy) {
                    lunes.setDate(lunes.getDate() + 7);
                    semanaNum++;
                    continue;
                }
                
                const option = document.createElement('option');
                option.value = semanaNum;
                option.textContent = `Semana ${semanaNum}: ${lunesStr} - ${sabadoStr}`;
                
                // Seleccionar la semana actual (donde hoy está dentro de la semana)
                if (esMesActual && lunes <= hoy && sabado >= hoy) {
                    option.selected = true;
                }
                
                semanaSelect.appendChild(option);
                
                lunes.setDate(lunes.getDate() + 7);
                semanaNum++;
            }
            
            if (semanaSelect.options.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No hay semanas disponibles';
                option.disabled = true;
                semanaSelect.appendChild(option);
            }
        }
        
        anioSelect.addEventListener('change', actualizarMesesDisponibles);
        mesSelect.addEventListener('change', calcularSemanasDisponibles);
        
        actualizarMesesDisponibles();
    });
  </script>

        <!-- modal mensual -->
        <div class="modal fade text-start" id="modalformularioCrearReporteMensual" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalReporteMensualLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalReporteMensualLabel">Generar Reporte Mensual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                <form action="{{ route('admin.entradas.reporte.mensual') }}" method="POST" target="_blank">
                  @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Mes</label>
                      <select name="mes" class="form-select" required>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Año</label>
                       <select name="anio" class="form-select" required>
                         @php
                             $currentYear = date('Y');
                             for ($y = $currentYear; $y >= $currentYear - 5; $y--):
                         @endphp
                             <option value="{{ $y }}">{{ $y }}</option>
                         @endfor
                       </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Servicio</label>
                      <select name="servicio" class="form-select">
                        <!-- <option value="0">Todos</option> -->
                        @foreach ($servicios as $servicio)
                         @if ($servicio->nombre == 'ALMUERZO')
                             <option value="{{ $servicio->nombre }}" selected>{{ $servicio->nombre }}</option>
                          @endif
                          <option value="{{ $servicio->nombre }}">{{ $servicio->nombre }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Tipo</label>
                      <select name="tipo" class="form-select">
                        <option value="TODOS">TODOS</option>
                        @foreach ($tipos as $tipo)
                          <option value="{{ $tipo }}">{{ $tipo }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-info w-100" type="submit">Generar Reporte Mensual</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- modal semanas del mes -->
        <div class="modal fade text-start" id="modalformularioCrearReporteSemanasMes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalReporteSemanalMesLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalReporteSemanalMesLabel">Generar Reporte Semanas del Mes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                <form action="{{ route('admin.entradas.reporte.semanas.mes') }}" method="POST" target="_blank">
                  @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Año</label>
                      <select name="anio" class="form-select" required>
                        @php
                            $currentYear = date('Y');
                            for ($y = $currentYear; $y >= $currentYear - 5; $y--):
                        @endphp
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Mes</label>
                      <select name="mes" class="form-select" required>
                        @php
                            $meses = [
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                            ];
                            $currentMonth = date('n');
                        @endphp
                        @foreach($meses as $num => $nombre)
                            @if($num <= $currentMonth)
                                <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Servicio</label>
                      <select name="servicio" class="form-select">
                        <!-- <option value="0">Todos</option> -->
                        @foreach ($servicios as $servicio)
                          @if ($servicio->nombre == 'ALMUERZO')
                              <option value="{{ $servicio->nombre }}" selected>{{ $servicio->nombre }}</option>
                          @endif
                          <option value="{{ $servicio->nombre }}">{{ $servicio->nombre }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Tipo</label>
                      <select name="tipo" class="form-select">
                        <option value="TODOS">TODOS</option>
                        @foreach ($tipos as $tipo)
                          <option value="{{ $tipo }}">{{ $tipo }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-warning w-100" type="submit">Generar Reporte Semanas del Mes</button>
                    </div>
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