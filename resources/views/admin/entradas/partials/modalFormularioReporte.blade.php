{{-- Botones de Acceso Rápido a los Reportes --}}
<div class="d-flex flex-wrap gap-2">
    <button type="button" class="btn btn-light text-primary fw-semibold shadow-sm rounded-pill px-3 py-2 border-0 d-inline-flex align-items-center"
            data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporte" title="Generar Reporte Diario">
        <i class="bi bi-file-earmark-bar-graph-fill me-2 fs-6"></i> Reporte Diario
    </button>

    <button type="button" class="btn btn-light text-success fw-semibold shadow-sm rounded-pill px-3 py-2 border-0 d-inline-flex align-items-center"
            data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporteSemanal" title="Generar Reporte Semanal">
        <i class="bi bi-calendar-week-fill me-2 fs-6"></i> Reporte Semanal
    </button>

    <button type="button" class="btn btn-light text-info fw-semibold shadow-sm rounded-pill px-3 py-2 border-0 d-inline-flex align-items-center"
            data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporteMensual" title="Generar Reporte Mensual">
        <i class="bi bi-calendar-month-fill me-2 fs-6"></i> Reporte Mensual
    </button>

    <button type="button" class="btn btn-light text-warning fw-semibold shadow-sm rounded-pill px-3 py-2 border-0 d-inline-flex align-items-center"
            data-bs-toggle="modal" data-bs-target="#modalformularioCrearReporteSemanasMes" title="Generar Resumen Semanas del Mes">
        <i class="bi bi-calendar3 me-2 fs-6"></i> Semanas del Mes
    </button>
</div>

<!-- ========================================== -->
<!-- 1. MODAL REPORTE DIARIO                    -->
<!-- ========================================== -->
<div class="modal fade text-start" id="modalformularioCrearReporte" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalReporteDiarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-bar-graph-fill fs-4 me-2"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalReporteDiarioLabel">Generar Reporte Diario</h5>
                        <small class="text-white-50">Distribución detallada por tipo y sexo para una fecha específica</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.entradas.reporte') }}" method="POST" target="_blank" id="formularioCrearReporte"
                    class="row g-3 needs-validation" novalidate>
                    @csrf
                    <div class="col-md-6">
                        <label for="fechaDiario" class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-calendar-date text-primary me-1"></i> Fecha del Reporte
                        </label>
                        <input type="date" class="form-control border-secondary-subtle" name="fecha" id="fechaDiario"
                            value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="servicioDiario" class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-cup-hot text-primary me-1"></i> Servicio
                        </label>
                        <select name="servicio" id="servicioDiario" class="form-select border-secondary-subtle">
                            <option value="0">Todos los servicios</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->nombre }}" {{ $servicio->nombre == 'ALMUERZO' ? 'selected' : '' }}>
                                    {{ $servicio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="tipoDiario" class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-people text-primary me-1"></i> Tipo de Comensal
                        </label>
                        <select name="tipo" id="tipoDiario" class="form-select border-secondary-subtle">
                            <option value="TODOS">TODOS (Desglose Estudiantes F/M, Empleados F/M y Apoyos)</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4 pt-2 border-top d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm" type="submit">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Generar PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. MODAL REPORTE SEMANAL                   -->
<!-- ========================================== -->
<div class="modal fade text-start" id="modalformularioCrearReporteSemanal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalReporteSemanalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-week-fill fs-4 me-2"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalReporteSemanalLabel">Generar Reporte Semanal</h5>
                        <small class="text-white-50">Informe oficial para Rectoría (Lunes a Sábado con balance semanal)</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.entradas.reporte.semanal') }}" method="POST" target="_blank" id="formularioReporteSemanal" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-calendar text-success me-1"></i> Año
                        </label>
                        <select name="anio" id="anioSemanal" class="form-select border-secondary-subtle" required>
                            @php
                                $currentYear = date('Y');
                                for ($y = $currentYear; $y >= $currentYear - 5; $y--):
                            @endphp
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-calendar2-month text-success me-1"></i> Mes
                        </label>
                        <select name="mes" id="mesSemanal" class="form-select border-secondary-subtle" required>
                            @php
                                $meses = [
                                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                ];
                                $currentMonth = date('n');
                            @endphp
                            @foreach($meses as $num => $nombre)
                                <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-calendar3-range text-success me-1"></i> Semana del Mes
                        </label>
                        <select name="semana" id="semanaSelect" class="form-select border-secondary-subtle" required>
                            <!-- Se carga dinámicamente con JS -->
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-cup-hot text-success me-1"></i> Servicio
                        </label>
                        <select name="servicio" class="form-select border-secondary-subtle">
                            <option value="0">Todos los servicios</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->nombre }}" {{ $servicio->nombre == 'ALMUERZO' ? 'selected' : '' }}>
                                    {{ $servicio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-people text-success me-1"></i> Tipo de Comensal
                        </label>
                        <select name="tipo" class="form-select border-secondary-subtle">
                            <option value="TODOS">TODOS (Desglose F/M)</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4 pt-2 border-top d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success rounded-pill px-4 shadow-sm" type="submit">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Generar Reporte Semanal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 3. MODAL REPORTE MENSUAL                   -->
<!-- ========================================== -->
<div class="modal fade text-start" id="modalformularioCrearReporteMensual" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalReporteMensualLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-info text-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-month-fill fs-4 me-2"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="modalReporteMensualLabel">Generar Reporte Mensual</h5>
                        <small class="text-white-50">Detalle día a día del mes completo con balance de género y apoyos</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.entradas.reporte.mensual') }}" method="POST" target="_blank" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-calendar2-month text-info me-1"></i> Mes
                        </label>
                        <select name="mes" class="form-select border-secondary-subtle" required>
                            @foreach($meses as $num => $nombre)
                                <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-calendar text-info me-1"></i> Año
                        </label>
                        <select name="anio" class="form-select border-secondary-subtle" required>
                            @php
                                for ($y = $currentYear; $y >= $currentYear - 5; $y--):
                            @endphp
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-cup-hot text-info me-1"></i> Servicio
                        </label>
                        <select name="servicio" class="form-select border-secondary-subtle">
                            <option value="0">Todos los servicios</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->nombre }}" {{ $servicio->nombre == 'ALMUERZO' ? 'selected' : '' }}>
                                    {{ $servicio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-people text-info me-1"></i> Tipo de Comensal
                        </label>
                        <select name="tipo" class="form-select border-secondary-subtle">
                            <option value="TODOS">TODOS (Desglose F/M)</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4 pt-2 border-top d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-info text-white rounded-pill px-4 shadow-sm" type="submit">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Generar Reporte Mensual
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 4. MODAL REPORTE SEMANAS DEL MES           -->
<!-- ========================================== -->
<div class="modal fade text-start" id="modalformularioCrearReporteSemanasMes" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalReporteSemanalMesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-warning text-dark py-3 border-0">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar3 fs-4 me-2"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="modalReporteSemanalMesLabel">Generar Reporte Semanas del Mes</h5>
                        <small class="text-dark-50">Resumen consolidado agrupado por semanas (sincronizado al 100% con el mes)</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.entradas.reporte.semanas.mes') }}" method="POST" target="_blank" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-calendar text-warning me-1"></i> Año
                        </label>
                        <select name="anio" class="form-select border-secondary-subtle" required>
                            @php
                                for ($y = $currentYear; $y >= $currentYear - 5; $y--):
                            @endphp
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-calendar2-month text-warning me-1"></i> Mes
                        </label>
                        <select name="mes" class="form-select border-secondary-subtle" required>
                            @foreach($meses as $num => $nombre)
                                <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-cup-hot text-warning me-1"></i> Servicio
                        </label>
                        <select name="servicio" class="form-select border-secondary-subtle">
                            <option value="0">Todos los servicios</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->nombre }}" {{ $servicio->nombre == 'ALMUERZO' ? 'selected' : '' }}>
                                    {{ $servicio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">
                            <i class="bi bi-people text-warning me-1"></i> Tipo de Comensal
                        </label>
                        <select name="tipo" class="form-select border-secondary-subtle">
                            <option value="TODOS">TODOS (Desglose F/M)</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mt-4 pt-2 border-top d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm" type="submit">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Generar Resumen Semanal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script de Cálculo Dinámico de Semanas --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anioSelect = document.getElementById('anioSemanal');
    const mesSelect = document.getElementById('mesSemanal');
    const semanaSelect = document.getElementById('semanaSelect');

    if (!anioSelect || !mesSelect || !semanaSelect) return;

    function calcularSemanasDisponibles() {
        const anio = parseInt(anioSelect.value);
        const mes = parseInt(mesSelect.value);

        if (!anio || !mes) return;

        semanaSelect.innerHTML = '';

        const primerDiaMes = new Date(anio, mes - 1, 1);
        const ultimoDiaMes = new Date(anio, mes, 0);

        // Encontrar el lunes de la semana que contiene el primer día del mes
        let cursorLunes = new Date(primerDiaMes);
        while (cursorLunes.getDay() !== 1) { // 1 = Lunes
            cursorLunes.setDate(cursorLunes.getDate() - 1);
        }

        let semanaNum = 1;
        const hoy = new Date();
        const esMesActual = hoy.getFullYear() === anio && (hoy.getMonth() + 1) === mes;

        while (cursorLunes <= ultimoDiaMes) {
            const sabado = new Date(cursorLunes);
            sabado.setDate(cursorLunes.getDate() + 5);

            // Verificar si esta semana tiene días en el mes seleccionado
            let tieneDiasEnMes = false;
            let iter = new Date(cursorLunes);
            while (iter <= sabado) {
                if (iter.getMonth() === mes - 1 && iter.getFullYear() === anio) {
                    tieneDiasEnMes = true;
                    break;
                }
                iter.setDate(iter.getDate() + 1);
            }

            if (tieneDiasEnMes) {
                const lunesStr = String(cursorLunes.getDate()).padStart(2, '0') + '/' + String(cursorLunes.getMonth() + 1).padStart(2, '0');
                const sabadoStr = String(sabado.getDate()).padStart(2, '0') + '/' + String(sabado.getMonth() + 1).padStart(2, '0');

                const option = document.createElement('option');
                option.value = semanaNum;
                option.textContent = `Semana ${semanaNum}: ${lunesStr} - ${sabadoStr}`;

                if (esMesActual && cursorLunes <= hoy && sabado >= hoy) {
                    option.selected = true;
                }

                semanaSelect.appendChild(option);
                semanaNum++;
            }

            cursorLunes.setDate(cursorLunes.getDate() + 7);
        }

        if (semanaSelect.options.length > 0 && !semanaSelect.value) {
            semanaSelect.options[0].selected = true;
        }
    }

    anioSelect.addEventListener('change', calcularSemanasDisponibles);
    mesSelect.addEventListener('change', calcularSemanasDisponibles);

    calcularSemanasDisponibles();
});
</script>