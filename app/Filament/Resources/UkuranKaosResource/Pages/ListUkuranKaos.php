<?php

namespace App\Filament\Resources\UkuranKaosResource\Pages;

use App\Filament\Resources\UkuranKaosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUkuranKaos extends ListRecords
{
    protected static string $resource = UkuranKaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
