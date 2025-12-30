<?php

namespace App\Filament\Resources\JamOperasionalResource\Pages;

use App\Filament\Resources\JamOperasionalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJamOperasionals extends ListRecords
{
    protected static string $resource = JamOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
