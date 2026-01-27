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

        /* Sección de firmas para impresión: usar tabla (compatible con dompdf) */
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .signatures-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 10px;
        }

        .sign-line {
            display: block;
            height: 90px; /* espacio para firmar a mano */
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
        }

        .stamp-box {
            display: block;
            width: 120px;
            height: 60px;
            border: none; /* sin rectángulo del sello */
            margin: 6px auto 0 auto; /* espacio para el sello */
        }

        /* Ajustes específicos para impresión */
        @media print {
            .container { width: 100%; margin: 0; }
            .signatures-table { margin-top: 50px; }
            tr, td { page-break-inside: avoid; }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
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
        <table class="signatures-table" role="presentation">
            <tr>
                <td>
                    <span class="sign-line"></span>
                    <div><strong>Por la Universidad</strong><br>Firma y Sello</div>
                    <span class="stamp-box" aria-hidden="true"></span>
                </td>
                <td>
                    <span class="sign-line"></span>
                    <div><strong>Por la Empresa</strong><br>Firma y Sello</div>
                    <span class="stamp-box" aria-hidden="true"></span>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
