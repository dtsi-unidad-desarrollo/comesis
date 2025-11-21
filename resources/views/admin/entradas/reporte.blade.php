<!-- filepath: resources/views/admin/entradas/reporte.blade.php -->
<!DOCTYPE html>
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
</html>