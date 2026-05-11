@php
    date_default_timezone_set('America/Mexico_City');
    setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');
    $fechaDia = strftime('%e de %B de %Y', strtotime(date('Y-m-d')));
    $img = asset('images/logo_nuevo.png');
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 400;
            src: url('{{ asset('fonts/Montserrat-Regular.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 700;
            src: url('{{ asset('fonts/Montserrat-Bold.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Times New Roman';
            font-style: normal;
            font-weight: 700;
            src: url('{{ asset('fonts/Times New Roman Bold.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Montserrat', sans-serif;
            font-size: 10pt;
            color: #333;
        }

        @page {
            margin-top: 5px;
            ;
            margin-bottom: 50mm;
            size: letter landscape;
        }

        .bold-text {
            font-weight: bold;
        }

        #header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
            line-height: 35px;
        }

        * {
            margin-bottom: 0px !important;
        }

        main {
            margin-bottom: 40px !important;
        }

        #footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            border-top: 1px solid gray;
            padding-top: 5px;
            height: 1.5cm;
        }

        .titulo {
            font-family: "Times New Roman", "Montserrat", serif;
            font-size: 11pt;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        <style>body {
            font-family: DejaVu Sans, sans-serif;
            color: #2d3748;
            font-size: 12px;
        }

        .table-container {
            width: 100%;
            margin-top: 15px;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 8px;
        }

        .modern-table thead th {
            background: #1e3a5f;
            color: #ffffff;
            padding: 12px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            border: 1px solid #d1d5db;
        }

        .modern-table tbody td {
            padding: 10px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .modern-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .modern-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .modern-table tbody tr:hover {
            background: #edf2f7;
        }

        .text-center {
            text-align: center;
        }

        .small {
            font-size: 10px;
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #2d3748;
            font-size: 12px;
        }

        .table-container {
            width: 100%;
            margin-top: 15px;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 8px;
        }

        .modern-table thead th {
            background: #1e3a5f;
            color: #ffffff;
            padding: 12px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            border: 1px solid #d1d5db;
        }

        .modern-table tbody td {
            padding: 10px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .modern-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .modern-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .modern-table tbody tr:hover {
            background: #edf2f7;
        }

        .text-center {
            text-align: center;
        }

        .small {
            font-size: 10px;
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>


<body>
    {{--  
    <header id="header">
        <div style="height: 100%">
            <img src="{{ $img }}" height="100px" style="float:left;margin-right:20px; padding-right:20px;">
            <p style="margin-top: 30px;line-height:.8;">
                <span class="bold-text titulo">UNIVERSIDAD DE GUADALAJARA </span><br>
                <span style="color:#7D91BE;font-size: 10pt;" class="bold-text"> CENTRO UNIVERSITARIO DE CIENCIAS
                    SOCIALES Y HUMANIDADES</span> <br>
                <span style="font-size: 8pt;">SECRETARÍA PRIVADA</span> <br>
                <span style="font-size: 8pt;">OFICIALIA DE PARTES</span>
            </p>
        </div>
    </header>
--}}
    <footer id="footer">

    </footer>

    <main style="clear: both;">
        <div style="width:100%;margin-top:1cm; margin-bottom: 20px !important;">
            <p style="line-height:.8;overflow-wrap: break-word;" class="bold-text text-center">
                REPORTE DE REGISTROS<br>

            </p>
        </div>
        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th width="5%" class="text-center"># Folio</th>
                        <th width="15%">No. Oficio</th>
                        <th width="12%">Fecha Oficio</th>
                        <th width="26%">Dependencia</th>
                        <th width="30%">Asunto</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($resultados as $item)
                        <tr>

                            <td>
                                <span class="badge">
                                    {{ $item->id }}
                                </span>
                            </td>

                            <td>
                                {{ $item->num_oficio }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->fecha_oficio)->format('d/m/Y') }}
                            </td>

                            <td>
                                <strong>{{ $item->envia->nombre }}</strong>
                                <div class="small">
                                    {{ $item->envia->dependencia }}
                                </div>
                            </td>

                            <td>
                                {{ $item->asunto }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
