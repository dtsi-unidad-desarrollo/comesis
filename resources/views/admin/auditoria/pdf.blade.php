<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Auditoría del sistema</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Auditoría del sistema</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha/Hora</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Entidad</th>
                <th>Detalles</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auditorias as $registro)
                <tr>
                    <td>{{ $registro->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $registro->user_name ?? 'Sistema' }}</td>
                    <td>{{ $registro->action }}</td>
                    <td>{{ $registro->entity_type ?? '-' }} {{ $registro->entity_id ? '#'.$registro->entity_id : '' }}</td>
                    <td>{{ $registro->details ? json_decode($registro->details, true)['message'] ?? $registro->details : '-' }}</td>
                    <td>{{ $registro->ip_address ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
