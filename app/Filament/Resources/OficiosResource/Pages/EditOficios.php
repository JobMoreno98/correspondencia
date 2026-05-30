<?php

namespace App\Filament\Resources\OficiosResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\OficiosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditOficios extends EditRecord
{
    protected static string $resource = OficiosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $original = $this->record->archivo;
        // Si el frontend ya no trae archivo válido
        if (
            empty($data['archivo']) ||
            ! Storage::disk('public')->exists($data['archivo'])
        ) {

            // borrar físico
            if ($original && Storage::disk('public')->exists($original)) {
                Storage::disk('public')->delete($original);
            }

            // limpiar DB
            $data['archivo'] = null;
        }

        return $data;
    }
}
