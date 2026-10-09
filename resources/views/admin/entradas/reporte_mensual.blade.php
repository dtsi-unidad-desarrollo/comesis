<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Mensual de Comedor - Sistema Comesis</title>
    <style>
        @page {
            margin: 0.6cm 0.8cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td {
            border: 1px solid #333;
            padding: 4px 6px;
            text-align: center;
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .title-hdr {
            font-size: 13px;
            color: #0d2e5c;
            letter-spacing: .5px;
        }

        .sub-hdr {
            font-size: 10px;
            color: #333;
            font-weight: normal;
        }

        /* Tabla Principal */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #333;
            padding: 2.5px 3px;
            height: 14px;
            text-align: center;
            font-size: 9px;
        }

        .main-table th {
            background-color: #e9ecef;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 8.5px;
        }

        .main-table th.sub-th {
            background-color: #f1f3f5;
            font-size: 8px;
            padding: 2px 1px;
        }

        .text-center {
            text-align: center;
        }

        .bg-subtotal {
            background-color: #f8fafc;
            font-weight: 600;
        }

        .bg-total-dia {
            background-color: #eef2f6;
            font-weight: bold;
        }

        .row-domingo {
            background-color: #fafafa;
            color: #888;
        }

        .total-row td {
            font-weight: bold;
            background-color: #cbd5e1;
            font-size: 9.5px;
            border-top: 2px solid #000;
        }

        /* Resumen porcentual */
        .resumen-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .resumen-table td {
            border: 1px solid #94a3b8;
            padding: 5px 8px;
            text-align: center;
            background-color: #f8fafc;
            font-size: 9.5px;
        }

        .resumen-val {
            font-size: 12px;
            font-weight: bold;
            color: #0d2e5c;
            margin-top: 2px;
        }

        /* Sección de Firmas */
        .signature-section {
            margin-top: 15px;
            width: 100%;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 50%;
            float: right;
        }

        .line {
            border-top: 1px solid #000;
            width: 220px;
            margin: 25px auto 4px auto;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td style="width: 12%; background: white;">
                <img src="{{ public_path('assets/img/logo.png') }}" alt="logo" style="height: 50px;"
                     onerror="this.style.display='none'">
            </td>
            <td style="width: 88%;">
                <div class="title-hdr">UNIVERSIDAD NACIONAL EXPERIMENTAL DE LOS LLANOS OCCIDENTALES EZEQUIEL ZAMORA</div>
                <div class="sub-hdr">COORDINACIÓN ENLACE DE BIENESTAR ESTUDIANTIL — COMEDOR SEDE BARINAS (VPDS)</div>
                <div style="font-size: 11px; margin-top: 2px; color: #0d6efd;">
                    REPORTE MENSUAL DE BANDEJAS — <strong>{{ strtoupper($mes) }} {{ $anio }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 14%;">DÍA | FECHA</th>
                <th colspan="3" style="width: 26%;">ESTUDIANTES</th>
                <th colspan="3" style="width: 26%;">EMPLEADOS</th>
                <th rowspan="2" style="width: 14%;">APOYOS / OTROS</th>
                <th rowspan="2" style="width: 20%;">TOTAL DÍA</th>
            </tr>
            <tr>
                <th class="sub-th" style="width: 8%;">FEM</th>
                <th class="sub-th" style="width: 8%;">MASC</th>
                <th class="sub-th" style="width: 10%;">SUBTOTAL</th>
                <th class="sub-th" style="width: 8%;">FEM</th>
                <th class="sub-th" style="width: 8%;">MASC</th>
                <th class="sub-th" style="width: 10%;">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dias as $dia)
            @if($dia->es_domingo && $dia->total_dia == 0)
            <tr class="row-domingo">
                <td>{{ $dia->fecha_label }} (DOM)</td>
                <td colspan="7" style="letter-spacing: 3px; font-size: 8px;">DOMINGO — CERRADO</td>
                <td>-</td>
            </tr>
            @else
            <tr>
                <td>{{ $dia->fecha_label }}</td>
                <td>{{ $dia->estudiantes_f > 0 ? $dia->estudiantes_f : '-' }}</td>
                <td>{{ $dia->estudiantes_m > 0 ? $dia->estudiantes_m : '-' }}</td>
                <td class="bg-subtotal">{{ $dia->total_estudiantes > 0 ? $dia->total_estudiantes : '-' }}</td>
                <td>{{ $dia->empleados_f > 0 ? $dia->empleados_f : '-' }}</td>
                <td>{{ $dia->empleados_m > 0 ? $dia->empleados_m : '-' }}</td>
                <td class="bg-subtotal">{{ $dia->total_empleados > 0 ? $dia->total_empleados : '-' }}</td>
                <td>{{ $dia->apoyos > 0 ? $dia->apoyos : '-' }}</td>
                <td class="bg-total-dia">{{ $dia->total_dia > 0 ? $dia->total_dia : '-' }}</td>
            </tr>
            @endif
            @endforeach

            <!-- Fila de Totales Generales del Mes -->
            <tr class="total-row">
                <td><strong>TOTALES MES</strong></td>
                <td><strong>{{ number_format($totales->estudiantes_f) }}</strong></td>
                <td><strong>{{ number_format($totales->estudiantes_m) }}</strong></td>
                <td style="color: #0d2e5c;"><strong>{{ number_format($totales->total_estudiantes) }}</strong></td>
                <td><strong>{{ number_format($totales->empleados_f) }}</strong></td>
                <td><strong>{{ number_format($totales->empleados_m) }}</strong></td>
                <td style="color: #0d2e5c;"><strong>{{ number_format($totales->total_empleados) }}</strong></td>
                <td><strong>{{ number_format($totales->apoyos) }}</strong></td>
                <td style="color: #0d6efd; font-size: 11px;"><strong>{{ number_format($granTotal) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Resumen Consolidado -->
    <table class="resumen-table">
        <tr>
            <td style="width: 25%;">
                <div>Total Estudiantes</div>
                <div class="resumen-val">{{ number_format($totales->total_estudiantes) }} <small>({{ $porcentajes['estudiantes'] }}%)</small></div>
            </td>
            <td style="width: 25%;">
                <div>Total Empleados</div>
                <div class="resumen-val">{{ number_format($totales->total_empleados) }} <small>({{ $porcentajes['empleados'] }}%)</small></div>
            </td>
            <td style="width: 25%;">
                <div>Total Apoyos / Otros</div>
                <div class="resumen-val">{{ number_format($totales->apoyos) }} <small>({{ $porcentajes['apoyos'] }}%)</small></div>
            </td>
            <td style="width: 25%; background-color: #e2e8f0;">
                <div><strong>GRAN TOTAL MES</strong></div>
                <div class="resumen-val" style="color: #0d6efd; font-size: 14px;">{{ number_format($granTotal) }}</div>
            </td>
        </tr>
    </table>

    <!-- Firma -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="line"></div>
            <strong>LCDO. JUZMAN MENDOZA</strong><br>
            <strong>C.I.: 16.475.763</strong><br>
            Coordinador General de Comedor — VPDS UNELLEZ
        </div>
    </div>

</body>

</html>