<?php

namespace App\Filament\Resources\KaosVarianResource\Pages;

use App\Filament\Resources\KaosVarianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKaosVarians extends ListRecords
{
    protected static string $resource = KaosVarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
