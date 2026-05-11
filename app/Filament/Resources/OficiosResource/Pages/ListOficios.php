<?php

namespace App\Filament\Resources\OficiosResource\Pages;

use App\Filament\Resources\OficiosResource;
use Filament\Actions;
use Filament\Actions\Action as ActionsAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class ListOficios extends ListRecords
{
    protected static string $resource = OficiosResource::class;

    // Estado local para el filtro
    public bool $filterRegistroHoy = false;

    // Cambiamos la query para incluir el filtro
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if ($this->filterRegistroHoy) {
            $query->whereDate('fecha_registro', today());
        }

        return $query;
    }
    protected function getListeners(): array
    {
        return [
            'refreshTable' => '$refresh',
        ];
    }

    // Mantener el botón Crear en el header general (opcional)
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionsAction::make('exportar')
                ->label('Generar reporte')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->url(function ($livewire) {

                    $fecha = $livewire->getTableFilterState('fecha_registro_rango');
                    $envia = $livewire->getTableFilterState('envia_id')['value'];
                    $dia = $livewire->getTableFilterState('registro_exacto')['fecha'];

                    return route('reporte.pdf', [
                        'envia_id' => $envia,
                        'fecha' => $fecha,
                        'dia' => $dia
                    ]);
                })->visible(
                    fn($livewire) =>
                    filled($livewire->getTableFilterState('fecha_registro_rango'))
                )
                ->openUrlInNewTab(),
        ];
    }
}
