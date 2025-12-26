<?php

namespace App\Filament\Resources\KaosResource\Pages;

use App\Filament\Resources\KaosResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKaos extends ViewRecord
{
    protected static string $resource = KaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
