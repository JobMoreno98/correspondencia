<?php

namespace App\Filament\Resources\DestinatarioResource\Pages;

use App\Filament\Resources\DestinatarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDestinatario extends ViewRecord
{
    protected static string $resource = DestinatarioResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
