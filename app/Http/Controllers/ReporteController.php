<?php

namespace App\Http\Controllers;

use App\Models\Oficios;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function reporte(Request $request)
    {
        $query = Oficios::query();

        // Filtrar por envia_id
        if ($request->filled('envia_id')) {
            $query->where('envia_id', $request->envia_id);
        }

        // Filtrar por rango de fechas
        if ($request->has('fecha.desde') && $request->has('fecha.hasta')) {
            $query->whereBetween('fecha_oficio', [$request->fecha['desde'], $request->fecha['hasta']]);
        }

        // Filtrar por día específico
        if ($request->filled('dia')) {
            $query->whereDate('fecha_registro', $request->dia);
        }

        $resultados = $query->get();

        //return response()->json($resultados);

        $html = view('reporte', compact('resultados'));

        $pdf = Pdf::loadHtml($html->render())->setWarnings(false)
            ->setPaper('letter', 'landscape')
            ->setOptions([
                'defaultFont' => 'Montserrat',
                'isRemoteEnabled' => true,
                'isFontSubsettingEnabled' => true,
            ]);

        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->get_canvas();
        $width = $canvas->get_width();
        $x_center = $width / 2 - 50;

        $canvas->page_text($x_center, 570, 'Parres Arias No. 150 Los Belenes C.P. 45132.', null, 8, [0, 0, 0]);
        $canvas->page_text(100, 580, 'www.cucsh.udg.mx', null, 11, '#7D91BE');
        $canvas->page_text($x_center, 580, 'Zapopan, Jalisco, México.   Tel. +52 (33) 38193300 Ext. 23409', null, 8, [0, 0, 0]);
        $canvas->page_text($x_center, 590, 'Página {PAGE_NUM} de {PAGE_COUNT}', null, 8, [0, 0, 0]);

        return $pdf->stream();
    }
}
