<?php

namespace App\Filament\Resources\DestinatarioResource\Pages;

use App\Filament\Resources\DestinatarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDestinatario extends EditRecord
{
    protected static string $resource = DestinatarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
