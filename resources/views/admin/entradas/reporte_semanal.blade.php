<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Semanal - Sistema Comesis</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 900px; margin: 0 auto; padding: 10px; }
        .hdr { text-align: center; margin-bottom: 10px; }
        .hdr .logos { width: 100%; border: none; margin-bottom: 5px; }
        .hdr .logos td { border: none; vertical-align: middle; }
        .hdr h2 { margin: 0; font-size: 16px; letter-spacing: .6px; }
        .hdr p { margin: 2px 0; font-size: 11px; }
        .fecha { text-align: right; margin-top: 8px; margin-bottom: 20px; font-size: 12px; }
        .saludo { margin: 0 0 10px; line-height: 1.4; font-size: 12px; text-align: justify; }
        .texto { margin: 0 0 10px; font-size: 12px; text-align: justify; }
        .datos { margin-bottom: 14px; }
        .datos strong { display: inline-block; width: 85px; }
        .table-wrap { margin-top: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; page-break-inside: avoid; }
        th, td { border: 1px solid #000; padding: 4px 6px; text-align: center; font-size: 12px; }
        th { background: #f2f2f2; font-weight: 700; }
        .detalle td { text-align: left; }
        .total-row td { font-weight: bold; font-size: 13px; }
        .firma { margin-top: 20px; }
        .firma .line { display: block; width: 220px; border-bottom: 1px solid #000; margin: 30px auto 4px auto; }
        .firma p { margin: 0; text-align: center; font-size: 12px; }
        .footer { margin-top: 8px; text-align: center; font-size: 11px; line-height: 1.2; }
        .sellos { width: 100%; margin-top: 12px; }
        .sellos td { border: none; text-align: center; vertical-align: top; padding: 0; }
        .sello-box { width: 130px; height: 80px; display: inline-block; border: 1px solid #000; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="hdr">
            <table class="logos">
                <tr>
                    <td style="text-align:left; width: 25%;"><img src="{{ asset('assets/img/logo.png') }}" alt="logo" style="height: 70px;"></td>
                    <td style="text-align:center; width: 50%;">
                        <h2>UNIVERSIDAD NACIONAL EXPERIMENTAL<br>DE LOS LLANOS OCCIDENTALES EZEQUIEL ZAMORA</h2>
                        <p>UNELLEZ</p>
                        <p>COORDINACIÓN ENLACE DE BIENESTAR ESTUDIANTIL - VPDS</p>
                    </td>
                    <td style="text-align:right; width: 25%;"></td>
                </tr>
            </table>
        </div>

        <p class="fecha"><strong>Fecha:</strong> {{ $fecha ?? now()->format('d/m/Y') }}</p>

        <div class="datos">
            <p><strong>Ciudadano:</strong></p>
            <p>Dr. Pedro Grima</p>
            <p>Rector UNELLEZ</p>
            <p>Su Despacho</p>
        </div>

        <p class="saludo">
            Reciba un saludo revolucionario, socialista antiimperialista y profundamente chavista, deseándoles éxitos en sus funciones; por medio de la presente me dirijo a usted muy respetuosamente, a fin de informar distribución de bandejas en el comedor de la sede Barinas del VPDS de la UNELLEZ desde el {{ $desde ?? 'dd/mm/yyyy' }} hasta el {{ $hasta ?? 'dd/mm/yyyy' }}.
        </p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>DIA</th>
                        <th>FECHA</th>
                        <th>BANDEJAS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diario as $fila)
                        <tr>
                            <td>{{ strtoupper($fila->dia) }}</td>
                            <td>{{ $fila->fecha }}</td>
                            <td>{{ number_format($fila->bandejas) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2" style="text-align: right;">TOTAL</td>
                        <td>{{ number_format($totalComidas ?? 0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="texto">Sin más a que referirme, me despido de usted Bolivariana y revolucionariamente.</p>

        <div class="firma">
            <span class="line"></span>
            <p><strong>Lcda. Yelitza Pirela B.</strong></p>
            <p>Coordinadora de Enlace de Bienestar Estudiantil del VPDS</p>
            <p>Designado mediante resolución N0102-2025</p>
        </div>

        <div class="footer">
            <p>Fecha 27/10/25</p>
            <p>0416-9729308 / yelitza.pirela27@gmail.com</p>
        </div>

        <table class="sellos">
            <tr>
                <td><div class="sello-box"></div><br><small>Sello</small></td>
                <td><div class="sello-box"></div><br><small>Sello</small></td>
            </tr>
        </table>
    </div>
</body>
</html>