<?php

namespace App\Filament\Resources\OngkirResource\Pages;

use App\Filament\Resources\OngkirResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOngkirs extends ListRecords
{
    protected static string $resource = OngkirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
