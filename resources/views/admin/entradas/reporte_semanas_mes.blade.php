<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .logo-placeholder {
            width: 50px;
            height: 50px;
            border: 1px solid #000;
        }
        
        /* Tabla Principal */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            height: 18px;
        }
        .main-table th {
            background-color: #e0e0e0;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bg-gray { background-color: #f2f2f2; }
        
        /* Sección de Firmas */
        .signature-section {
            margin-top: 30px;
            width: 100%;
        }
        .signature-box {
            text-align: center;
            width: 50%;
            float: right;
        }
        .line {
            border-top: 1px solid #000;
            width: 250px;
            margin: 0 auto 5px auto;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 10%; background: white;">
                <div class="logo-placeholder">LOGO</div>
            </td>
            <td style="font-size: 16px;">REPORTE DE COMEDOR - RESUMEN SEMANAL</td>
        </tr>
        <tr>
            <td colspan="2" class="bg-gray">{{ strtoupper($mes) }} | {{ $anio }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 25%;">DESDE</th>
                <th style="width: 25%;">HASTA</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $granTotal = 0; @endphp
            @foreach($semanas as $semana)
                @php 
                    $total = $semana->total;
                    $granTotal += $total;
                @endphp
                <tr>
                    <td class="text-center">{{ $semana->desde->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $semana->hasta->format('d/m/Y') }}</td>
                    <td class="text-center bg-gray">{{ $total > 0 ? number_format($total) : '' }}</td>
                </tr>
            @endforeach

            <tr>
                <td class="bg-gray"><strong>TOTAL MES</strong></td>
                <td colspan="1"></td>
                <td class="text-center"><strong>{{ number_format($granTotal) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <br><br><br>
            <div class="line"></div>
            <strong>Yelitza Pineda B.</strong><br>
            Coord. Bienestar Estudiantil VPDS
        </div>
    </div>

</body>
</html>
