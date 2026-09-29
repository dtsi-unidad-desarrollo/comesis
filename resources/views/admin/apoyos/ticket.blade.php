<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tickets de Apoyo - {{ $apoyo->codigo }}</title>
    <style>
        @page {
            margin: 5mm 5mm 5mm 5mm;
            size: letter portrait;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            background-color: #ffffff;
            font-size: 7.5px;
            line-height: 1.1;
        }

        .page-container {
            width: 100%;
        }

        .grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px 4px;
            table-layout: fixed;
        }

        .grid-td {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        .ticket-card {
            width: 100%;
            height: 70px;
            border: 1px dashed #64748b;
            border-radius: 4px;
            background: #ffffff;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .ticket-main {
            padding: 4px 6px;
            vertical-align: middle;
            width: 72%;
            border-right: 1px dashed #94a3b8;
        }

        .ticket-stub {
            padding: 2px 4px;
            vertical-align: middle;
            text-align: center;
            width: 28%;
            background-color: #f8fafc;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .logo-cell {
            width: 22px;
            vertical-align: middle;
        }

        .logo-img {
            max-width: 20px;
            max-height: 20px;
        }

        .title-cell {
            vertical-align: middle;
            padding-left: 3px;
        }

        .app-name {
            font-size: 8.5px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.3px;
            line-height: 1;
        }

        .badge-type {
            display: inline-block;
            background-color: #0284c7;
            color: #ffffff;
            font-size: 5.5px;
            font-weight: bold;
            padding: 1px 3px;
            border-radius: 2px;
            text-transform: uppercase;
        }

        .inst-box {
            background-color: #f1f5f9;
            border-left: 2px solid #0284c7;
            padding: 2px 4px;
            margin: 2px 0;
            border-radius: 0 2px 2px 0;
        }

        .inst-label {
            font-size: 5.5px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
        }

        .inst-name {
            font-size: 7.5px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ticket-meta {
            width: 100%;
            border-collapse: collapse;
            font-size: 6px;
            color: #475569;
            margin-top: 1px;
        }

        .ticket-meta td {
            padding: 0;
        }

        .meta-val {
            font-weight: bold;
            color: #0f172a;
        }

        .badge-ticket-num {
            background-color: #0f172a;
            color: #ffffff;
            padding: 1px 3px;
            border-radius: 2px;
            font-weight: bold;
            font-size: 6px;
        }

        .qr-img {
            width: 44px;
            height: 44px;
            display: block;
            margin: 0 auto;
        }

        .stub-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 6.5px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 1px;
            letter-spacing: 0.3px;
        }

        .stub-badge {
            font-size: 5.5px;
            color: #0284c7;
            font-weight: bold;
            text-transform: uppercase;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @php
        $totalTickets = $cantidad;
        $pages = array_chunk(range(1, $totalTickets), 20);
    @endphp

    @foreach ($pages as $pageIndex => $pageTickets)
        <div class="page-container">
            <table class="grid-table">
                @foreach (array_chunk($pageTickets, 2) as $row)
                    <tr>
                        @foreach ($row as $ticketIndex)
                            <td class="grid-td">
                                <table class="ticket-card">
                                    <tr>
                                        <!-- Cuerpo Principal -->
                                        <td class="ticket-main">
                                            <table class="header-table">
                                                <tr>
                                                    @if(!empty($logoBase64))
                                                        <td class="logo-cell">
                                                            <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo">
                                                        </td>
                                                    @endif
                                                    <td class="title-cell">
                                                        <div class="app-name">COMESIS</div>
                                                    </td>
                                                    <td style="text-align: right; vertical-align: middle;">
                                                        <span class="badge-type">Pase de Apoyo</span>
                                                    </td>
                                                </tr>
                                            </table>

                                            <div class="inst-box">
                                                <span class="inst-label">Beneficiario:</span>
                                                <div class="inst-name">{{ $apoyo->institucion }}</div>
                                            </div>

                                            <table class="ticket-meta">
                                                <tr>
                                                    <td><span class="badge-ticket-num">#{{ $ticketIndex }}/{{ $totalTickets }}</span></td>
                                                    <td>Válido: <span class="meta-val">1 Comida</span></td>
                                                    <td style="text-align: right;"><span class="meta-val">{{ $fechaEmision }}</span></td>
                                                </tr>
                                            </table>
                                        </td>

                                        <!-- Talón de Control -->
                                        <td class="ticket-stub">
                                            @if(!empty($qrBase64))
                                                <img class="qr-img" src="{{ $qrBase64 }}" alt="QR">
                                            @endif
                                            <div class="stub-code">{{ $apoyo->codigo }}</div>
                                            <div class="stub-badge">#{{ $ticketIndex }}/{{ $totalTickets }}</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        @endforeach
                        @if (count($row) == 1)
                            <td class="grid-td"></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
