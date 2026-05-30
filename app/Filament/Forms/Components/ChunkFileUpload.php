<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class ChunkFileUpload extends Field
{
    protected string $view = 'filament.forms.components.chunk-file-upload';

    protected string | null $uploadUrl = null;

    public function uploadUrl(string $url): static
    {
        $this->uploadUrl = $url;
        return $this;
    }

    public function getUploadUrl(): string
    {
        return $this->uploadUrl ?? route('api.chunks.upload');
    }

    public function isFieldDisabled(): bool
    {
        return $this->isDisabled();
    }
}
