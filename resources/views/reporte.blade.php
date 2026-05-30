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
        @page {
            margin-top: 10px;
            margin-left: 15px;
            margin-right: 15px;
            margin-bottom: 70px;
            size: letter landscape;
        }

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
        * {
            margin-bottom: 0px !important;
        }

        body {
            font-family: 'Montserrat', DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #2d3748;
        }

        #header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
        }

        #footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            text-align: center;
            border-top: 1px solid #cbd5e0;
            padding-top: 5px;
            height: 1.5cm;
            font-size: 10px;
            color: #718096;
        }

        main {
            margin-top: 20px;
            margin-bottom: 40px;
            padding-bottom: 80px;
        }

        /* =========================
       TEXTOS
    ========================== */

        .titulo {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            font-weight: bold;
        }

        .bold-text {
            font-weight: bold;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .small {
            font-size: 9px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* =========================
       TABLA
    ========================== */

        .table-container {
            width: 100%;
            margin-top: 15px;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            table-layout: fixed;
        }

        /* COLUMNAS */
        .w-folio {
            width: 8%;
        }

        .w-oficio {
            width: 10%;
        }

        .w-fecha {
            width: 12%;
        }

        .w-envia {
            width: 20%;
        }

        .w-turna {
            width: 20%;
        }

        .w-asunto {
            width: 30%;
        }

        /* HEADERS */
        .modern-table thead th {
            background-color: #1e3a5f;
            color: #ffffff;
            padding: 8px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
            border: 1px solid #cbd5e0;

            word-wrap: break-word;
            word-break: break-word;
        }

        /* CELDAS */
        .modern-table tbody td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 10px;
            line-height: 1.4;

            word-wrap: break-word;
            word-break: break-word;
        }

        /* FILAS PARES */
        .modern-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* FILAS IMPARES */
        .modern-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* =========================
       BADGE
    ========================== */

        .badge {
            display: inline-block;
            padding: 2px 6px;
            background-color: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #93c5fd;
            font-size: 9px;
            font-weight: bold;
        }

        /* =========================
       SALTOS DE PÁGINA
    ========================== */

        tr {
            page-break-inside: avoid;
        }

        table {
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        /* =========================
       UTILIDADES
    ========================== */

        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }
    </style>
</head>


<body>
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
                        <th class="w-folio"># Folio</th>
                        <th class="w-oficio">No. Oficio</th>
                        <th class="w-fecha">Fecha Oficio</th>
                        <th class="w-envia">Envia</th>
                        <th class="w-turna">Turna</th>
                        <th class="w-asunto">Asunto</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($resultados as $item)
                        <tr>
                            <td>{{ $item->id }}</td>

                            <td>
                                {{ $item->num_oficio }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->fecha_oficio)->format('d/m/Y') }}
                            </td>

                            <td>
                                <strong>{{ $item->envia->nombre }}</strong>
                                <div>
                                    {{ $item->envia->dependencia }}
                                </div>
                            </td>

                            <td>
                                <strong>{{ $item->recibe->nombre }}</strong>
                                <div>
                                    {{ $item->recibe->dependencia }}
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
