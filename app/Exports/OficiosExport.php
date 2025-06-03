<?php
namespace App\Exports;

use App\Models\Oficios;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class OficiosExport extends ExcelExport
{
    protected string $name = 'Exportación de Oficios';

    protected Closure|string|null $filename;

    public function __construct()
    {
        $this->filename = now()->format('Y-m-d') . '-oficios.xlsx';
    }

    public function query(): Builder
    {
        return Oficios::query()->with(['envia', 'recibe']);
    }
}
