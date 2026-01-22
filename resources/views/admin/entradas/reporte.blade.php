<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte del comedor - Sistema Comesis</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .container { width: 95%; margin: 10px auto; }
        .header { text-align: center; margin-bottom: 10px; }
        .summary { margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height:60px;">
            <h2>Reporte de Comidas</h2>
            <p><strong>Fecha:</strong> {{ isset($fecha) && $fecha ? \Carbon\Carbon::parse($fecha)->format('d-m-Y') : '' }}</p>
        </div>

        <div class="summary">
            <p><strong>Total Almuerzos:</strong> {{ $totalAlmuerzos ?? 0 }}</p>
            <p><strong>Total Cenas:</strong> {{ $totalCenas ?? 0 }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tipo de comensal</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @php $tipoSel = isset($tipoSeleccionado) ? strtoupper($tipoSeleccionado) : null; @endphp

                @if(!$tipoSel || $tipoSel === 'ESTUDIANTE')
                <tr>
                    <td>ESTUDIANTE</td>
                    <td>{{ $totalEstudiantes ?? 0 }}</td>
                </tr>
                @endif

                @if(!$tipoSel || $tipoSel === 'ESTUDIANTE FORANEO')
                <tr>
                    <td>ESTUDIANTE FORANEO</td>
                    <td>{{ $totalEstudiantesForaneos ?? 0 }}</td>
                </tr>
                @endif

                 @if(!$tipoSel || $tipoSel === 'EMPLEADO')
                <tr>
                    <td>EMPLEADO</td>
                    <td>{{ $totalEmpleados ?? 0 }}</td>
                </tr>
                @endif

                <!-- @if(!$tipoSel || $tipoSel === 'PROFESOR')
                <tr>
                    <td>PROFESOR</td>
                    <td>{{ $totalProfesor ?? 0 }}</td>
                </tr>
                @endif -->

                <!-- @if(!$tipoSel || $tipoSel === 'ADMINISTRATIVO')
                <tr>
                    <td>ADMINISTRATIVO</td>
                    <td>{{ $totalAdministrativos ?? 0 }}</td>
                </tr>
                @endif -->

                @if(!$tipoSel || $tipoSel === 'EVENTUAL')
                <tr>
                    <td>EVENTUAL</td>
                    <td>{{ $totalEventual ?? 0 }}</td>
                </tr>
                @endif

                <!-- @if(!$tipoSel || $tipoSel === 'OBRERO')
                <tr>
                    <td>OBRERO</td>
                    <td>{{ $totalObreros ?? 0 }}</td>
                </tr>
                @endif -->
            </tbody>
        </table>
    </div>
</body>
</html>
