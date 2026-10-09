<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Reporte Semanal - Sistema Comesis</title>
    <style>
        @page {
            margin: 0.8cm 1cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .hdr-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .hdr-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .hdr h2 {
            margin: 0;
            font-size: 13.5px;
            letter-spacing: .4px;
            color: #0d2e5c;
            line-height: 1.3;
        }

        .hdr p {
            margin: 2px 0;
            font-size: 10px;
            color: #444;
        }

        .fecha-top {
            text-align: right;
            margin-top: 4px;
            margin-bottom: 12px;
            font-size: 10.5px;
        }

        .datos {
            margin-bottom: 10px;
            font-size: 11px;
            line-height: 1.3;
        }

        .datos strong {
            display: inline-block;
        }

        .saludo {
            margin: 0 0 10px;
            line-height: 1.4;
            font-size: 10.5px;
            text-align: justify;
        }

        .texto {
            margin: 10px 0 8px;
            font-size: 10.5px;
            text-align: justify;
        }

        /* Tabla de distribución semanal */
        table.semanal-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        table.semanal-table th,
        table.semanal-table td {
            border: 1px solid #333;
            padding: 4px 4px;
            text-align: center;
            font-size: 10px;
        }

        table.semanal-table th {
            background-color: #f1f3f5;
            font-weight: 700;
            text-transform: uppercase;
        }

        table.semanal-table th.sub-th {
            background-color: #e9ecef;
            font-size: 9px;
            padding: 3px 2px;
        }

        .bg-subtotal {
            background-color: #f8fafc;
            font-weight: 600;
        }

        .bg-total-dia {
            background-color: #eef2f6;
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            background-color: #e2e8f0;
            font-size: 10.5px;
            border-top: 2px solid #000;
        }

        .balance-row td {
            font-weight: bold;
            background-color: #f1f5f9;
            font-size: 10px;
        }

        /* Firma y sellos */
        .firma {
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .firma .line {
            display: block;
            width: 210px;
            border-bottom: 1px solid #000;
            margin: 25px auto 4px auto;
        }

        .firma p {
            margin: 1px 0;
            text-align: center;
            font-size: 10.5px;
        }

        .sellos {
            width: 100%;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .sellos td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 0;
            width: 50%;
        }

        .sello-box {
            width: 110px;
            height: 55px;
            display: inline-block;
            border: 1px dashed #777;
            margin-top: 4px;
            color: #888;
            font-size: 9px;
            line-height: 55px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Encabezado -->
        <table class="hdr-table">
            <tr>
                <td style="text-align:left; width: 20%;">
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="logo" style="height: 75px;"
                         onerror="this.style.display='none'">
                </td>
                <td style="text-align:center; width: 60%;" class="hdr">
                    <h2>UNIVERSIDAD NACIONAL EXPERIMENTAL<br>DE LOS LLANOS OCCIDENTALES EZEQUIEL ZAMORA</h2>
                    <p><strong>UNELLEZ — LA UNIVERSIDAD QUE SIEMBRA</strong></p>
                    <p>COORDINACIÓN ENLACE DE BIENESTAR ESTUDIANTIL — VPDS BARINAS</p>
                </td>
                <td style="text-align:right; width: 20%;">
                    <div style="font-size: 9.5px; color: #555; text-align: right;">
                        Servicio: <strong>{{ $servicioSeleccionado }}</strong><br>
                        Filtro: <strong>{{ $tipoSeleccionado }}</strong>
                    </div>
                </td>
            </tr>
        </table>

        <p class="fecha-top"><strong>Barinas,</strong> {{ $fecha ?? now()->format('d/m/Y') }}</p>

        <div class="datos">
            <p style="margin: 0;"><strong>Ciudadano:</strong></p>
            <p style="margin: 2px 0 0 0;">Prof. Katiuska Angulo</p>
            <p style="margin: 2px 0 0 0;">Rectora UNELLEZ</p>
            <p style="margin: 2px 0 0 0;">Su Despacho.-</p>
        </div>

        <p class="saludo">
            Reciba un cordial y revolucionario saludo, deseándole éxitos en sus funciones. Por medio de la presente, me dirijo a usted muy respetuosamente a fin de informar la distribución de bandejas del comedor en la sede Barinas del VPDS de la UNELLEZ, correspondiente a la <strong>Semana {{ $semanaNumero ?? '' }} (desde el {{ $desde }} hasta el {{ $hasta }})</strong>, con el desglose detallado por sexo y tipo de comensal:
        </p>

        <!-- Tabla Desglosada -->
        <table class="semanal-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 13%;">DÍA</th>
                    <th rowspan="2" style="width: 11%;">FECHA</th>
                    <th colspan="3" style="width: 25%;">ESTUDIANTES</th>
                    <th colspan="3" style="width: 25%;">EMPLEADOS</th>
                    <th rowspan="2" style="width: 11%;">APOYOS / OTROS</th>
                    <th rowspan="2" style="width: 15%;">TOTAL BANDEJAS</th>
                </tr>
                <tr>
                    <th class="sub-th" style="width: 7%;">FEM</th>
                    <th class="sub-th" style="width: 7%;">MASC</th>
                    <th class="sub-th" style="width: 11%;">SUBTOTAL</th>
                    <th class="sub-th" style="width: 7%;">FEM</th>
                    <th class="sub-th" style="width: 7%;">MASC</th>
                    <th class="sub-th" style="width: 11%;">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diario as $fila)
                <tr>
                    <td><strong>{{ strtoupper($fila->dia) }}</strong></td>
                    <td>{{ $fila->fecha }}</td>
                    <td>{{ $fila->estudiantes_f > 0 ? number_format($fila->estudiantes_f) : '-' }}</td>
                    <td>{{ $fila->estudiantes_m > 0 ? number_format($fila->estudiantes_m) : '-' }}</td>
                    <td class="bg-subtotal">{{ $fila->total_estudiantes > 0 ? number_format($fila->total_estudiantes) : '-' }}</td>
                    <td>{{ $fila->empleados_f > 0 ? number_format($fila->empleados_f) : '-' }}</td>
                    <td>{{ $fila->empleados_m > 0 ? number_format($fila->empleados_m) : '-' }}</td>
                    <td class="bg-subtotal">{{ $fila->total_empleados > 0 ? number_format($fila->total_empleados) : '-' }}</td>
                    <td>{{ $fila->apoyos > 0 ? number_format($fila->apoyos) : '-' }}</td>
                    <td class="bg-total-dia">{{ number_format($fila->total_dia) }}</td>
                </tr>
                @endforeach

                <!-- Fila de Totales de la Semana -->
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">TOTALES SEMANA:</td>
                    <td>{{ number_format($totales->estudiantes_f) }}</td>
                    <td>{{ number_format($totales->estudiantes_m) }}</td>
                    <td style="color: #0d2e5c;">{{ number_format($totales->total_estudiantes) }}</td>
                    <td>{{ number_format($totales->empleados_f) }}</td>
                    <td>{{ number_format($totales->empleados_m) }}</td>
                    <td style="color: #0d2e5c;">{{ number_format($totales->total_empleados) }}</td>
                    <td>{{ number_format($totales->apoyos) }}</td>
                    <td style="color: #0d6efd; font-size: 11.5px;">{{ number_format($totalComidas) }}</td>
                </tr>

                <!-- Filas de Balance Semanal -->
                <tr class="balance-row">
                    <td colspan="8" style="text-align: right;">CAPACIDAD PROGRAMADA DE LA SEMANA:</td>
                    <td colspan="2">{{ number_format($capacidadSemanal) }}</td>
                </tr>
                <tr class="balance-row" style="color: #991b1b;">
                    <td colspan="8" style="text-align: right;">BANDEJAS SIN ENTREGAR (CAPACIDAD − TOTAL ENTREGADAS):</td>
                    <td colspan="2">{{ number_format($bandejasSinEntregar) }}</td>
                </tr>
            </tbody>
        </table>

        <p class="texto">Sin más a que referirme, reiterando nuestro compromiso con la comunidad unellista, me despido respetuosamente.</p>

        <!-- Firmas -->
        <div class="firma">
            <span class="line"></span>
            <p><strong>LCDO. JUZMAN MENDOZA</strong></p>
            <p><strong>C.I.: 16.475.763</strong></p>
            <p>Coordinador General de Comedor — VPDS</p>
        </div>

        <table class="sellos">
            <tr>
                <td>
                    <div class="sello-box">Sello Bienestar Estudiantil</div>
                </td>
                <td>
                    <div class="sello-box">Sello Coordinación Comedor</div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>