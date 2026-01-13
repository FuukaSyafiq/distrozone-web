<?php

namespace App\Filament\Resources\KaosResource\Pages;

use App\Filament\Resources\KaosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKaos extends ListRecords
{
    protected static string $resource = KaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
            Actions\CreateAction::make('kaos')->label('Tambah Kaos'),
        ];
    }
}
