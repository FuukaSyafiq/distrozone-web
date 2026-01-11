<?php

namespace App\Filament\Resources\JamOperasionalResource\Pages;

use App\Filament\Resources\JamOperasionalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJamOperasional extends EditRecord
{
    protected static string $resource = JamOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
