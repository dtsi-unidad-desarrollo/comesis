<?php

namespace App\Services;

use App\Models\Entrada;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReporteComedorService
{
    /**
     * Días de la semana en español.
     */
    public const DIAS_SEMANA = [
        'Monday'    => 'Lunes',
        'Tuesday'   => 'Martes',
        'Wednesday' => 'Miercoles',
        'Thursday'  => 'Jueves',
        'Friday'    => 'Viernes',
        'Saturday'  => 'Sabado',
        'Sunday'    => 'Domingo',
    ];

    /**
     * Meses en español.
     */
    public const MESES = [
        1  => 'ENERO',
        2  => 'FEBRERO',
        3  => 'MARZO',
        4  => 'ABRIL',
        5  => 'MAYO',
        6  => 'JUNIO',
        7  => 'JULIO',
        8  => 'AGOSTO',
        9  => 'SEPTIEMBRE',
        10 => 'OCTUBRE',
        11 => 'NOVIEMBRE',
        12 => 'DICIEMBRE',
    ];

    /**
     * Obtiene el nombre del mes.
     */
    public static function getMesNombre(int $mes): string
    {
        return self::MESES[$mes] ?? '';
    }

    /**
     * Normaliza el sexo de una entrada ('F', 'M', o 'N/A').
     */
    public static function normalizarSexo(?string $sexo): string
    {
        if (empty($sexo)) {
            return 'N/A';
        }
        $primera = strtoupper(substr(trim($sexo), 0, 1));
        if ($primera === 'F') {
            return 'F';
        }
        if ($primera === 'M') {
            return 'M';
        }
        return 'N/A';
    }

    /**
     * Calcula la capacidad para un servicio (o la suma de todos) dado un número de días.
     */
    public static function calcularCapacidad(?string $servicio, int $dias = 1): int
    {
        $dias = max(1, $dias);
        if ($servicio && $servicio !== '0' && $servicio !== 'TODOS') {
            $disp = (int) Servicio::where('nombre', $servicio)->value('disponibilidad');
        } else {
            $disp = (int) Servicio::sum('disponibilidad');
        }
        return $disp * $dias;
    }

    /**
     * Divide un mes en semanas (Lunes a Sábado), asegurando que todas las fechas
     * del mes queden cubiertas sin omisiones ni inclusiones de otros meses.
     */
    public static function obtenerSemanasDelMes(int $anio, int $mes): array
    {
        $primerDia = Carbon::createFromDate($anio, $mes, 1)->startOfDay();
        $ultimoDia = $primerDia->copy()->endOfMonth()->startOfDay();

        // Encontrar el lunes de la semana que contiene el primer día del mes
        $cursorLunes = $primerDia->copy();
        while ($cursorLunes->dayOfWeek !== 1) { // 1 = Lunes en Carbon
            $cursorLunes->subDay();
        }

        $semanas = [];
        $numeroSemana = 1;

        while ($cursorLunes <= $ultimoDia) {
            $sabado = $cursorLunes->copy()->addDays(5); // Sábado

            $fechasEnMes = [];
            $iter = $cursorLunes->copy();
            while ($iter <= $sabado) {
                if ($iter->month == $mes && $iter->year == $anio) {
                    $fechasEnMes[] = $iter->format('Y-m-d');
                }
                $iter->addDay();
            }

            if (!empty($fechasEnMes)) {
                $desdeCarbon = Carbon::parse($fechasEnMes[0]);
                $hastaCarbon = Carbon::parse(end($fechasEnMes));

                $semanas[] = [
                    'numero'          => $numeroSemana,
                    'lunes'           => $cursorLunes->copy(),
                    'sabado'          => $sabado->copy(),
                    'desde'           => $desdeCarbon,
                    'hasta'           => $hastaCarbon,
                    'desde_label'     => $desdeCarbon->format('d/m/Y'),
                    'hasta_label'     => $hastaCarbon->format('d/m/Y'),
                    'fechas_en_mes'   => $fechasEnMes,
                    'fechas_completas'=> [
                        $cursorLunes->copy()->format('Y-m-d'),
                        $cursorLunes->copy()->addDays(1)->format('Y-m-d'),
                        $cursorLunes->copy()->addDays(2)->format('Y-m-d'),
                        $cursorLunes->copy()->addDays(3)->format('Y-m-d'),
                        $cursorLunes->copy()->addDays(4)->format('Y-m-d'),
                        $sabado->copy()->format('Y-m-d'),
                    ],
                ];
                $numeroSemana++;
            }

            $cursorLunes->addDays(7);
        }

        return $semanas;
    }

    /**
     * Procesa una colección o lista de entradas y retorna el desglose matemático por sexo y tipo.
     */
    public static function procesarDesglose(Collection $entradas): array
    {
        $estudiantesF = 0;
        $estudiantesM = 0;
        $empleadosF = 0;
        $empleadosM = 0;
        $apoyos = 0;
        $eventuales = 0;
        $otros = 0;

        foreach ($entradas as $entrada) {
            $tipo = strtoupper(trim($entrada->tipo_comensal ?? ''));
            $sexo = self::normalizarSexo($entrada->sexo ?? null);

            if ($tipo === 'ESTUDIANTE' || $tipo === 'ESTUDIANTE FORANEO') {
                if ($sexo === 'F') {
                    $estudiantesF++;
                } else {
                    $estudiantesM++;
                }
            } elseif ($tipo === 'EMPLEADO') {
                if ($sexo === 'F') {
                    $empleadosF++;
                } else {
                    $empleadosM++;
                }
            } elseif ($tipo === 'APOYO') {
                $apoyos++;
            } elseif ($tipo === 'EVENTUAL') {
                $eventuales++;
            } else {
                $otros++;
            }
        }

        $totalEstudiantes = $estudiantesF + $estudiantesM;
        $totalEmpleados   = $empleadosF + $empleadosM;
        $totalApoyosOtros = $apoyos + $eventuales + $otros;
        $totalComidas     = $totalEstudiantes + $totalEmpleados + $totalApoyosOtros;

        $totalFemenino    = $estudiantesF + $empleadosF;
        $totalMasculino   = $estudiantesM + $empleadosM;

        return [
            'estudiantes_f'     => $estudiantesF,
            'estudiantes_m'     => $estudiantesM,
            'total_estudiantes' => $totalEstudiantes,
            'empleados_f'       => $empleadosF,
            'empleados_m'       => $empleadosM,
            'total_empleados'   => $totalEmpleados,
            'apoyos'            => $apoyos,
            'eventuales'        => $eventuales,
            'otros'             => $otros,
            'total_apoyos_otros'=> $totalApoyosOtros,
            'total_femenino'    => $totalFemenino,
            'total_masculino'   => $totalMasculino,
            'total_comidas'     => $totalComidas,
        ];
    }

    /**
     * Datos para el Reporte Diario.
     */
    public function getReporteDiarioData(string $fecha, ?string $servicio = null, ?string $tipo = 'TODOS'): array
    {
        $query = Entrada::whereDate('fecha', $fecha);

        if ($servicio && $servicio !== '0' && $servicio !== 'TODOS') {
            $query->where('comida', $servicio);
        }

        if ($tipo && $tipo !== 'TODOS') {
            if ($tipo === 'ESTUDIANTE') {
                $query->whereIn('tipo_comensal', ['ESTUDIANTE', 'ESTUDIANTE FORANEO']);
            } else {
                $query->where('tipo_comensal', $tipo);
            }
        }

        $entradas = $query->get();
        $desglose = self::procesarDesglose($entradas);

        $capacidadDiaria = self::calcularCapacidad($servicio, 1);
        $bandejasSinEntregar = max(0, $capacidadDiaria - $desglose['total_comidas']);

        return array_merge($desglose, [
            'fecha'                => $fecha,
            'fecha_formateada'     => Carbon::parse($fecha)->format('d/m/Y'),
            'servicioSeleccionado' => $servicio && $servicio !== '0' ? $servicio : 'TODOS',
            'tipoSeleccionado'     => $tipo ?: 'TODOS',
            'capacidadDiaria'      => $capacidadDiaria,
            'bandejasSinEntregar'  => $bandejasSinEntregar,
        ]);
    }

    /**
     * Datos para el Reporte Semanal (1 semana de Lunes a Sábado).
     */
    public function getReporteSemanalData(int $anio, int $mes, int $semanaNum, ?string $servicio = null, ?string $tipo = 'TODOS'): ?array
    {
        $semanas = self::obtenerSemanasDelMes($anio, $mes);
        $semanaObj = null;
        foreach ($semanas as $s) {
            if ($s['numero'] === $semanaNum) {
                $semanaObj = $s;
                break;
            }
        }

        if (!$semanaObj) {
            return null;
        }

        $fechasRango = $semanaObj['fechas_completas']; // Lunes a Sábado completo
        $query = Entrada::whereIn('fecha', $fechasRango);

        if ($servicio && $servicio !== '0' && $servicio !== 'TODOS') {
            $query->where('comida', $servicio);
        }

        if ($tipo && $tipo !== 'TODOS') {
            if ($tipo === 'ESTUDIANTE') {
                $query->whereIn('tipo_comensal', ['ESTUDIANTE', 'ESTUDIANTE FORANEO']);
            } else {
                $query->where('tipo_comensal', $tipo);
            }
        }

        $entradasAgrupadas = $query->get()->groupBy('fecha');

        $diario = collect();
        $totales = [
            'estudiantes_f'     => 0,
            'estudiantes_m'     => 0,
            'total_estudiantes' => 0,
            'empleados_f'       => 0,
            'empleados_m'       => 0,
            'total_empleados'   => 0,
            'apoyos'            => 0,
            'total_comidas'     => 0,
        ];

        foreach ($fechasRango as $f) {
            $fCarbon = Carbon::parse($f);
            $diaEn = $fCarbon->format('l');
            $diaEs = self::DIAS_SEMANA[$diaEn] ?? $diaEn;

            $entradasDia = $entradasAgrupadas->get($f, collect());
            $desgloseDia = self::procesarDesglose($entradasDia);

            $diario->push((object)[
                'dia'              => $diaEs,
                'fecha'            => $fCarbon->format('d/m/Y'),
                'fecha_iso'        => $f,
                'estudiantes_f'    => $desgloseDia['estudiantes_f'],
                'estudiantes_m'    => $desgloseDia['estudiantes_m'],
                'total_estudiantes'=> $desgloseDia['total_estudiantes'],
                'empleados_f'      => $desgloseDia['empleados_f'],
                'empleados_m'      => $desgloseDia['empleados_m'],
                'total_empleados'  => $desgloseDia['total_empleados'],
                'apoyos'           => $desgloseDia['total_apoyos_otros'],
                'total_dia'        => $desgloseDia['total_comidas'],
            ]);

            $totales['estudiantes_f']     += $desgloseDia['estudiantes_f'];
            $totales['estudiantes_m']     += $desgloseDia['estudiantes_m'];
            $totales['total_estudiantes'] += $desgloseDia['total_estudiantes'];
            $totales['empleados_f']       += $desgloseDia['empleados_f'];
            $totales['empleados_m']       += $desgloseDia['empleados_m'];
            $totales['total_empleados']   += $desgloseDia['total_empleados'];
            $totales['apoyos']            += $desgloseDia['total_apoyos_otros'];
            $totales['total_comidas']     += $desgloseDia['total_comidas'];
        }

        // Capacidad semanal (6 días: Lunes a Sábado)
        $capacidadSemanal = self::calcularCapacidad($servicio, count($fechasRango));
        $bandejasSinEntregar = max(0, $capacidadSemanal - $totales['total_comidas']);

        return [
            'fecha'               => now()->format('d/m/Y'),
            'desde'               => $semanaObj['lunes']->format('d/m/Y'),
            'hasta'               => $semanaObj['sabado']->format('d/m/Y'),
            'semanaNumero'        => $semanaNum,
            'diario'              => $diario,
            'totales'             => (object) $totales,
            'totalComidas'        => $totales['total_comidas'],
            'capacidadSemanal'    => $capacidadSemanal,
            'bandejasSinEntregar' => $bandejasSinEntregar,
            'servicioSeleccionado'=> $servicio && $servicio !== '0' ? $servicio : 'TODOS',
            'tipoSeleccionado'    => $tipo ?: 'TODOS',
        ];
    }

    /**
     * Datos para el Reporte Mensual (todos los días del mes).
     */
    public function getReporteMensualData(int $anio, int $mes, ?string $servicio = null, ?string $tipo = 'TODOS'): array
    {
        $inicioMes = Carbon::createFromDate($anio, $mes, 1)->startOfDay();
        $finMes = $inicioMes->copy()->endOfMonth()->endOfDay();

        $query = Entrada::whereBetween('fecha', [$inicioMes->format('Y-m-d'), $finMes->format('Y-m-d')]);

        if ($servicio && $servicio !== '0' && $servicio !== 'TODOS') {
            $query->where('comida', $servicio);
        }

        if ($tipo && $tipo !== 'TODOS') {
            if ($tipo === 'ESTUDIANTE') {
                $query->whereIn('tipo_comensal', ['ESTUDIANTE', 'ESTUDIANTE FORANEO']);
            } else {
                $query->where('tipo_comensal', $tipo);
            }
        }

        $entradasAgrupadas = $query->get()->groupBy('fecha');

        $diasEnElMes = $finMes->day;
        $dias = collect();
        $totales = [
            'estudiantes_f'     => 0,
            'estudiantes_m'     => 0,
            'total_estudiantes' => 0,
            'empleados_f'       => 0,
            'empleados_m'       => 0,
            'total_empleados'   => 0,
            'apoyos'            => 0,
            'gran_total'        => 0,
        ];

        for ($d = 1; $d <= $diasEnElMes; $d++) {
            $fechaCarbon = Carbon::createFromDate($anio, $mes, $d);
            $fechaIso = $fechaCarbon->format('Y-m-d');
            $esDomingo = $fechaCarbon->isSunday();
            $diaEn = $fechaCarbon->format('l');
            $diaEs = self::DIAS_SEMANA[$diaEn] ?? $diaEn;

            $entradasDia = $entradasAgrupadas->get($fechaIso, collect());
            $desglose = self::procesarDesglose($entradasDia);

            $dias->push((object)[
                'dia_numero'       => $d,
                'fecha'            => $fechaIso,
                'fecha_label'      => $fechaCarbon->format('d/m/Y'),
                'dia_nombre'       => $diaEs,
                'es_domingo'       => $esDomingo,
                'estudiantes_f'    => $desglose['estudiantes_f'],
                'estudiantes_m'    => $desglose['estudiantes_m'],
                'total_estudiantes'=> $desglose['total_estudiantes'],
                'empleados_f'      => $desglose['empleados_f'],
                'empleados_m'      => $desglose['empleados_m'],
                'total_empleados'  => $desglose['total_empleados'],
                'apoyos'           => $desglose['total_apoyos_otros'],
                'total_dia'        => $desglose['total_comidas'],
            ]);

            $totales['estudiantes_f']     += $desglose['estudiantes_f'];
            $totales['estudiantes_m']     += $desglose['estudiantes_m'];
            $totales['total_estudiantes'] += $desglose['total_estudiantes'];
            $totales['empleados_f']       += $desglose['empleados_f'];
            $totales['empleados_m']       += $desglose['empleados_m'];
            $totales['total_empleados']   += $desglose['total_empleados'];
            $totales['apoyos']            += $desglose['total_apoyos_otros'];
            $totales['gran_total']        += $desglose['total_comidas'];
        }

        // Porcentajes
        $granTotal = $totales['gran_total'];
        $porcentajes = [
            'estudiantes' => $granTotal > 0 ? round(($totales['total_estudiantes'] / $granTotal) * 100, 1) : 0,
            'empleados'   => $granTotal > 0 ? round(($totales['total_empleados'] / $granTotal) * 100, 1) : 0,
            'apoyos'      => $granTotal > 0 ? round(($totales['apoyos'] / $granTotal) * 100, 1) : 0,
        ];

        return [
            'mes'                  => self::getMesNombre($mes),
            'mesNumero'            => $mes,
            'anio'                 => $anio,
            'dias'                 => $dias,
            'totales'              => (object) $totales,
            'granTotal'            => $granTotal,
            'porcentajes'          => $porcentajes,
            'servicioSeleccionado' => $servicio && $servicio !== '0' ? $servicio : 'TODOS',
            'tipoSeleccionado'     => $tipo ?: 'TODOS',
        ];
    }

    /**
     * Datos para el Reporte Semanas del Mes (Resumen Semanal del Mes).
     */
    public function getReporteSemanasMesData(int $anio, int $mes, ?string $servicio = null, ?string $tipo = 'TODOS'): array
    {
        $semanasInfo = self::obtenerSemanasDelMes($anio, $mes);
        $semanas = collect();

        $totales = [
            'estudiantes_f'     => 0,
            'estudiantes_m'     => 0,
            'total_estudiantes' => 0,
            'empleados_f'       => 0,
            'empleados_m'       => 0,
            'total_empleados'   => 0,
            'apoyos'            => 0,
            'gran_total'        => 0,
        ];

        foreach ($semanasInfo as $s) {
            $query = Entrada::whereIn('fecha', $s['fechas_en_mes']);

            if ($servicio && $servicio !== '0' && $servicio !== 'TODOS') {
                $query->where('comida', $servicio);
            }

            if ($tipo && $tipo !== 'TODOS') {
                if ($tipo === 'ESTUDIANTE') {
                    $query->whereIn('tipo_comensal', ['ESTUDIANTE', 'ESTUDIANTE FORANEO']);
                } else {
                    $query->where('tipo_comensal', $tipo);
                }
            }

            $entradasSemana = $query->get();
            $desglose = self::procesarDesglose($entradasSemana);

            $semanas->push((object)[
                'numero'           => $s['numero'],
                'desde'            => $s['desde'],
                'hasta'            => $s['hasta'],
                'desde_label'      => $s['desde_label'],
                'hasta_label'      => $s['hasta_label'],
                'estudiantes_f'    => $desglose['estudiantes_f'],
                'estudiantes_m'    => $desglose['estudiantes_m'],
                'total_estudiantes'=> $desglose['total_estudiantes'],
                'empleados_f'      => $desglose['empleados_f'],
                'empleados_m'      => $desglose['empleados_m'],
                'total_empleados'  => $desglose['total_empleados'],
                'apoyos'           => $desglose['total_apoyos_otros'],
                'total'            => $desglose['total_comidas'],
            ]);

            $totales['estudiantes_f']     += $desglose['estudiantes_f'];
            $totales['estudiantes_m']     += $desglose['estudiantes_m'];
            $totales['total_estudiantes'] += $desglose['total_estudiantes'];
            $totales['empleados_f']       += $desglose['empleados_f'];
            $totales['empleados_m']       += $desglose['empleados_m'];
            $totales['total_empleados']   += $desglose['total_empleados'];
            $totales['apoyos']            += $desglose['total_apoyos_otros'];
            $totales['gran_total']        += $desglose['total_comidas'];
        }

        return [
            'mes'                  => self::getMesNombre($mes),
            'mesNumero'            => $mes,
            'anio'                 => $anio,
            'semanas'              => $semanas,
            'totales'              => (object) $totales,
            'granTotal'            => $totales['gran_total'],
            'servicioSeleccionado' => $servicio && $servicio !== '0' ? $servicio : 'TODOS',
            'tipoSeleccionado'     => $tipo ?: 'TODOS',
        ];
    }
}
