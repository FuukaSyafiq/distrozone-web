<?php

namespace App\Filament\Resources\MerekKaosResource\Pages;

use App\Filament\Resources\MerekKaosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMerekKaos extends ListRecords
{
    protected static string $resource = MerekKaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
