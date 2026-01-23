<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte del comedor - Sistema Comesis</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .container {
            width: 95%;
            margin: 10px auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .summary {
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
<<<<<<< HEAD
            <table style="width: 100%; border: none; margin-bottom: 20px;">
                <tr>
                    <td style="text-align: left; vertical-align: middle; border: none;">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 90px;">
                    </td>
                    <td style="text-align: center; vertical-align: middle; border: none;">
                        <h2 style="margin: 0; font-size: 18px;">Reporte del comedor</h2>
                        <p style="margin: 5px 0 0 0; font-size: 12px;">Sistema Comesis</p>
                    </td>
                    <td style="text-align: right; vertical-align: middle; border: none;">
                        <p style="margin: 0; font-size: 12px; color: #6b0101;"><strong>Fecha:</strong> {{ $fecha }}</p>
                    </td>
                </tr>
            </table>
=======
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height:60px;">
            <h2>Reporte de Comidas</h2>
            <p><strong>Fecha:</strong> {{ isset($fecha) && $fecha ? \Carbon\Carbon::parse($fecha)->format('d-m-Y') : '' }}</p>
        </div>

        <div class="summary">
            <p><strong>Total Almuerzos:</strong> {{ $totalAlmuerzos ?? 0 }}</p>
            <p><strong>Total Cenas:</strong> {{ $totalCenas ?? 0 }}</p>
>>>>>>> 89ab6e8341cbefb711c59e8c2104e3dac6c76a54
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tipo de comensal</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reporte as $tipo => $cantidad)
                    <tr>
                        <td>{{ $tipo }}</td>
                        <td>{{ $cantidad }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="align-content: right;"><strong>Total</strong></td>
                    <td><strong>{{ $totalComidas ?? 0 }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
