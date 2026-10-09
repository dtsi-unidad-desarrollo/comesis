<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resumen Semanal del Mes - Sistema Comesis</title>
    <style>
        @page {
            margin: 0.8cm 1cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
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
            letter-spacing: .4px;
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
            margin-top: 8px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #333;
            padding: 5px 4px;
            text-align: center;
            font-size: 9.5px;
        }

        .main-table th {
            background-color: #e9ecef;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 9px;
        }

        .main-table th.sub-th {
            background-color: #f1f3f5;
            font-size: 8.5px;
            padding: 3px 2px;
        }

        .bg-subtotal {
            background-color: #f8fafc;
            font-weight: 600;
        }

        .bg-total-sem {
            background-color: #eef2f6;
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            background-color: #cbd5e1;
            font-size: 10.5px;
            border-top: 2px solid #000;
        }

        /* Resumen consolidado */
        .resumen-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .resumen-table td {
            border: 1px solid #94a3b8;
            padding: 6px 10px;
            text-align: center;
            background-color: #f8fafc;
            font-size: 10px;
        }

        .resumen-val {
            font-size: 13px;
            font-weight: bold;
            color: #0d2e5c;
            margin-top: 2px;
        }

        /* Sección de Firmas */
        .signature-section {
            margin-top: 25px;
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
                <img src="{{ public_path('assets/img/logo.png') }}" alt="logo" style="height: 55px;"
                     onerror="this.style.display='none'">
            </td>
            <td style="width: 88%;">
                <div class="title-hdr">UNIVERSIDAD NACIONAL EXPERIMENTAL DE LOS LLANOS OCCIDENTALES EZEQUIEL ZAMORA</div>
                <div class="sub-hdr">COORDINACIÓN ENLACE DE BIENESTAR ESTUDIANTIL — COMEDOR SEDE BARINAS (VPDS)</div>
                <div style="font-size: 11px; margin-top: 2px; color: #b45309;">
                    RESUMEN SEMANAL DEL MES — <strong>{{ strtoupper($mes) }} {{ $anio }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 12%;">SEMANA</th>
                <th rowspan="2" style="width: 22%;">PERÍODO (FECHAS)</th>
                <th colspan="3" style="width: 24%;">ESTUDIANTES</th>
                <th colspan="3" style="width: 24%;">EMPLEADOS</th>
                <th rowspan="2" style="width: 9%;">APOYOS</th>
                <th rowspan="2" style="width: 9%;">TOTAL</th>
            </tr>
            <tr>
                <th class="sub-th" style="width: 7%;">FEM</th>
                <th class="sub-th" style="width: 7%;">MASC</th>
                <th class="sub-th" style="width: 10%;">SUBTOTAL</th>
                <th class="sub-th" style="width: 7%;">FEM</th>
                <th class="sub-th" style="width: 7%;">MASC</th>
                <th class="sub-th" style="width: 10%;">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($semanas as $semana)
            <tr>
                <td><strong>Semana {{ $semana->numero }}</strong></td>
                <td>{{ $semana->desde_label }} al {{ $semana->hasta_label }}</td>
                <td>{{ $semana->estudiantes_f > 0 ? number_format($semana->estudiantes_f) : '-' }}</td>
                <td>{{ $semana->estudiantes_m > 0 ? number_format($semana->estudiantes_m) : '-' }}</td>
                <td class="bg-subtotal">{{ $semana->total_estudiantes > 0 ? number_format($semana->total_estudiantes) : '-' }}</td>
                <td>{{ $semana->empleados_f > 0 ? number_format($semana->empleados_f) : '-' }}</td>
                <td>{{ $semana->empleados_m > 0 ? number_format($semana->empleados_m) : '-' }}</td>
                <td class="bg-subtotal">{{ $semana->total_empleados > 0 ? number_format($semana->total_empleados) : '-' }}</td>
                <td>{{ $semana->apoyos > 0 ? number_format($semana->apoyos) : '-' }}</td>
                <td class="bg-total-sem">{{ number_format($semana->total) }}</td>
            </tr>
            @endforeach

            <!-- Fila de Totales del Mes -->
            <tr class="total-row">
                <td colspan="2" style="text-align: right;"><strong>TOTAL MES:</strong></td>
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
            <td style="width: 30%;">
                <div>Total Estudiantes (F + M)</div>
                <div class="resumen-val">{{ number_format($totales->total_estudiantes) }}</div>
            </td>
            <td style="width: 30%;">
                <div>Total Empleados (F + M)</div>
                <div class="resumen-val">{{ number_format($totales->total_empleados) }}</div>
            </td>
            <td style="width: 20%;">
                <div>Apoyos Institucionales</div>
                <div class="resumen-val">{{ number_format($totales->apoyos) }}</div>
            </td>
            <td style="width: 20%; background-color: #e2e8f0;">
                <div><strong>GRAN TOTAL MES</strong></div>
                <div class="resumen-val" style="color: #0d6efd;">{{ number_format($granTotal) }}</div>
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