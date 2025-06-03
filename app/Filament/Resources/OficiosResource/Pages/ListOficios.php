<?php
namespace App\Filament\Resources\OficiosResource\Pages;

use App\Filament\Resources\OficiosResource;
use Filament\Actions;
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

    // Aquí definimos el botón en el header de la tabla
    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('registro_hoy')
                ->label($this->filterRegistroHoy ? 'Mostrar todos' : 'Registrados Hoy')
                ->color($this->filterRegistroHoy ? 'danger' : 'primary')
                ->action(function () {
                    $this->filterRegistroHoy = ! $this->filterRegistroHoy;
                    $this->emitSelf('refreshTable');
                }),
        ];
    }

    // Listener para refrescar la tabla al cambiar filtro
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
        ];
    }
}
