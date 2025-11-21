<!-- filepath: resources/views/admin/entradas/reporte.blade.php -->
{{-- <!DOCTYPE html>
<html>
<head>
    <title>Reporte de Comidas</title>
</head>
<body>
    <h1>Reporte de Comidas para la Fecha: {{ $fecha }}</h1>
    <p>Total de Almuerzos: {{ $totalAlmuerzos ?? 0 }}</p>
    <p>Total de Cenas: {{ $totalCenas ?? 0}}</p>
    <p>Total de Estudiantes: {{ $totalEstudiantes ?? 0}}</p>
    <p>Total de Estudiantes Foráneos: {{ $totalEstudiantesForaneos ?? 0 }}</p>
    <p>Total de Obreros: {{ $totalObreros ?? 0}}</p>
    <p>Total de Administrativos: {{ $totalAdministrativos ?? 0}}</p>
    <p>Total de Profesores: {{ $totalProfesor ?? 0}}</p>
    <p>Total de Eventuales: {{ $totalEventual ?? 0}}</p>
</body>
</html> --}}

<!DOCTYPE html>
<html>

<head>
    <title>Reporte del comedor - Sistema Comesis</title>
    <style>
        .reporte-incidencia {
            width: 90%;
            margin: 20px auto;
            font-family: sans-serif;
        }

        .encabezado {
            overflow: hidden;
            /* Asegura que el contenedor "encabezado" contenga los floats */
            margin-bottom: 20px;

        }

        .logo-titulo {
            overflow: hidden;
            /* Asegura que el contenedor "logo-titulo" contenga los floats */
        }

        .logo {
            float: left;
            margin-right: 20px;
        }

        .logo img {
            max-height: 70px;

        }

        .titulo {

            float: left;
            width: 100%;

        }

        .titulo h1 {
            margin: 0;
            padding: 0;

        }


        .tabla-incidencias {
            border-collapse: collapse;
            width: 100%;
        }

        .tabla-incidencias th,
        .tabla-incidencias td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .tabla-incidencias th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .tabla-incidencias td:nth-child(1) {
            width: 20%;
        }

        .tabla-incidencias td:nth-child(2) {
            width: 80%;
        }
    </style>
</head>

<body>
    <!-- Encabezado con logo, título y rango de emisión -->
    <div class="reporte-incidencia">
        <div class="encabezado">
            <div class="logo-titulo">
                <div class="logo">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Comesis">
                </div>
                <div class="titulo">
                    <h1>Reporte de Incidencia</h1>
                    <hr>
                    <div class="rango-emision">
                        <p><strong>Rango de emisión: Desde:</strong> {{ $fechaInicio->toDateString() }}
                            <strong>Hasta:</strong> {{ $fechaFin->toDateString() }}
                        </p>
                    </div>
                </div>


                <div style="clear: both;"></div>
            </div>
        </div>


        <!-- Listado de incidencias -->
        @foreach ($incidencias as $incidencia)
            <table class="tabla-incidencias">
                <thead>
                    <tr>
                        <th>FECHA Y HORA:</th>
                        <th>INCIDENCIA: {{ $incidencia->tipoIncidencia->nombre }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p>{{ $incidencia->fecha_apertura }}</p>
                            <p><strong>Estatus:</strong> {{ $incidencia->estatus }}</p>
                        </td>
                        <td>
                            <p>Total de Almuerzos: {{ $totalAlmuerzos ?? 0 }}</p>
                            <p>Total de Cenas: {{ $totalCenas ?? 0 }}</p>
                            <p>Total de Estudiantes: {{ $totalEstudiantes ?? 0 }}</p>
                            <p>Total de Estudiantes Foráneos: {{ $totalEstudiantesForaneos ?? 0 }}</p>
                            <p>Total de Obreros: {{ $totalObreros ?? 0 }}</p>
                            <p>Total de Administrativos: {{ $totalAdministrativos ?? 0 }}</p>
                            <p>Total de Profesores: {{ $totalProfesor ?? 0 }}</p>
                            <p>Total de Eventuales: {{ $totalEventual ?? 0 }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
</body>

</html>
