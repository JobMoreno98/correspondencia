<?php

namespace App\Filament\Resources\OficiosResource\Pages;

use App\Filament\Resources\OficiosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOficios extends EditRecord
{
    protected static string $resource = OficiosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
