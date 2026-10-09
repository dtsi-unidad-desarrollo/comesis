<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Reporte Diario del Comedor - Sistema Comesis</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #222;
            margin: 0;
            padding: 10px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 8px;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
            padding: 4px;
        }

        .title-main {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #0d2e5c;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .subtitle {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #555;
        }

        .badge-info {
            background-color: #f0f4f8;
            border: 1px solid #d0dbe5;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 11px;
            text-align: right;
            line-height: 1.4;
        }

        /* KPI summary table */
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }

        .kpi-table td {
            width: 33.33%;
            border: 1px solid #c9d6df;
            padding: 8px 10px;
            text-align: center;
            background: #fafbfc;
        }

        .kpi-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 3px;
        }

        .kpi-value {
            font-size: 16px;
            font-weight: bold;
            color: #0d2e5c;
        }

        .kpi-value.delivered {
            color: #198754;
        }

        .kpi-value.undelivered {
            color: #d63384;
        }

        /* Tabla principal de desglose */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 11px;
        }

        table.data-table th {
            background-color: #e9ecef;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            font-size: 10.5px;
        }

        table.data-table .text-start {
            text-align: left;
        }

        table.data-table .text-center {
            text-align: center;
        }

        table.data-table .text-end {
            text-align: right;
        }

        .row-group {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .row-total {
            background-color: #e2e8f0;
            font-weight: bold;
            font-size: 11.5px;
        }

        .row-balance {
            background-color: #f1f5f9;
            font-weight: bold;
        }

        /* Sección de firmas */
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 35px;
        }

        .signatures-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 15px;
            border: none;
        }

        .sign-line {
            display: block;
            height: 75px;
            border-bottom: 1px solid #000;
            margin-bottom: 6px;
        }

        .stamp-box {
            display: block;
            width: 110px;
            height: 55px;
            border: 1px dashed #999;
            margin: 6px auto 0 auto;
            color: #aaa;
            font-size: 9px;
            line-height: 55px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Encabezado Oficial -->
        <table class="header-table">
            <tr>
                <td style="width: 18%; text-align: left;">
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo UNELLEZ" style="height: 80px;"
                         onerror="this.style.display='none'">
                </td>
                <td style="width: 54%; text-align: center;">
                    <div class="title-main">Universidad Nacional Experimental<br>de los Llanos Occidentales Ezequiel Zamora</div>
                    <div class="subtitle">VICERRECTORADO DE PLANIFICACIÓN Y DESARROLLO SOCIAL — SEDE BARINAS</div>
                    <div class="subtitle" style="font-weight: bold; color: #0d6efd; margin-top: 4px;">
                        REPORTE DIARIO DE COMEDOR (CONTROL DE BANDEJAS)
                    </div>
                </td>
                <td style="width: 28%; text-align: right;">
                    <div class="badge-info">
                        <strong>Fecha:</strong> {{ $fecha_formateada }}<br>
                        <strong>Servicio:</strong> {{ $servicioSeleccionado }}<br>
                        <strong>Filtro:</strong> {{ $tipoSeleccionado }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Resumen de Métricas / KPIs -->
        <table class="kpi-table">
            <tr>
                <td>
                    <div class="kpi-label">Capacidad del Servicio</div>
                    <div class="kpi-value">{{ number_format($capacidadDiaria) }}</div>
                </td>
                <td>
                    <div class="kpi-label">Bandejas Entregadas</div>
                    <div class="kpi-value delivered">{{ number_format($total_comidas) }}</div>
                </td>
                <td>
                    <div class="kpi-label">Bandejas Sin Entregar</div>
                    <div class="kpi-value undelivered">{{ number_format($bandejasSinEntregar) }}</div>
                </td>
            </tr>
        </table>

        <!-- Tabla de Desglose por Tipo y Sexo -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40%; text-align: left;">Tipo de Comensal</th>
                    <th style="width: 20%;">Femenino (F)</th>
                    <th style="width: 20%;">Masculino (M)</th>
                    <th style="width: 20%;">Total Comensales</th>
                </tr>
            </thead>
            <tbody>
                <!-- Estudiantes -->
                <tr>
                    <td class="text-start">
                        <strong>ESTUDIANTES</strong>
                        <div style="font-size: 9.5px; color: #555;">(Regulares y Foráneos)</div>
                    </td>
                    <td class="text-center">{{ number_format($estudiantes_f) }}</td>
                    <td class="text-center">{{ number_format($estudiantes_m) }}</td>
                    <td class="text-center" style="font-weight: bold; background: #f8fafc;">{{ number_format($total_estudiantes) }}</td>
                </tr>

                <!-- Empleados -->
                <tr>
                    <td class="text-start">
                        <strong>EMPLEADOS</strong>
                        <div style="font-size: 9.5px; color: #555;">(Docente, Administrativo, Obrero)</div>
                    </td>
                    <td class="text-center">{{ number_format($empleados_f) }}</td>
                    <td class="text-center">{{ number_format($empleados_m) }}</td>
                    <td class="text-center" style="font-weight: bold; background: #f8fafc;">{{ number_format($total_empleados) }}</td>
                </tr>

                <!-- Apoyos Institucionales -->
                <tr>
                    <td class="text-start">
                        <strong>APOYOS INSTITUCIONALES</strong>
                        <div style="font-size: 9.5px; color: #555;">(Convenios y Autorizaciones Especiales)</div>
                    </td>
                    <td class="text-center" style="color: #777;">—</td>
                    <td class="text-center" style="color: #777;">—</td>
                    <td class="text-center" style="font-weight: bold; background: #f8fafc;">{{ number_format($apoyos) }}</td>
                </tr>

                @if(($eventuales + $otros) > 0)
                <tr>
                    <td class="text-start">
                        <strong>INVITADOS / EVENTUALES</strong>
                    </td>
                    <td class="text-center" style="color: #777;">—</td>
                    <td class="text-center" style="color: #777;">—</td>
                    <td class="text-center" style="font-weight: bold; background: #f8fafc;">{{ number_format($eventuales + $otros) }}</td>
                </tr>
                @endif

                <!-- Fila de Totales de Entradas -->
                <tr class="row-total">
                    <td class="text-start"><strong>TOTAL BANDEJAS ENTREGADAS</strong></td>
                    <td class="text-center"><strong>{{ number_format($total_femenino) }}</strong></td>
                    <td class="text-center"><strong>{{ number_format($total_masculino) }}</strong></td>
                    <td class="text-center" style="font-size: 13px; color: #0d6efd;"><strong>{{ number_format($total_comidas) }}</strong></td>
                </tr>

                <!-- Fila de Balance Matemático -->
                <tr class="row-balance">
                    <td colspan="3" class="text-end">CAPACIDAD PROGRAMADA DEL SERVICIO:</td>
                    <td class="text-center"><strong>{{ number_format($capacidadDiaria) }}</strong></td>
                </tr>
                <tr class="row-balance">
                    <td colspan="3" class="text-end" style="color: #991b1b;">BANDEJAS SIN ENTREGAR (CAPACIDAD − ENTREGADAS):</td>
                    <td class="text-center" style="color: #991b1b;"><strong>{{ number_format($bandejasSinEntregar) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Sección de Firmas y Sellos -->
        <table class="signatures-table" role="presentation">
            <tr>
                <td>
                    <span class="sign-line"></span>
                    <div><strong>Por la Universidad (UNELLEZ)</strong><br>Coordinación de Comedor / VPDS<br>Firma y Sello</div>
                    <span class="stamp-box">Sello Oficial</span>
                </td>
                <td>
                    <span class="sign-line"></span>
                    <div><strong>Por la Empresa Prestadora de Servicio</strong><br>Administración / Concesionario<br>Firma y Sello</div>
                    <span class="stamp-box">Sello Oficial</span>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
