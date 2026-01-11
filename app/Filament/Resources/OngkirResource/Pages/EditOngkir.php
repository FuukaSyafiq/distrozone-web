<?php

namespace App\Filament\Resources\OngkirResource\Pages;

use App\Filament\Resources\OngkirResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOngkir extends EditRecord
{
    protected static string $resource = OngkirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
