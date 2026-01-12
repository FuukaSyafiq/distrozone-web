<?php

namespace App\Filament\Resources\TypeKaosResource\Pages;

use App\Filament\Resources\TypeKaosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypeKaos extends ListRecords
{
    protected static string $resource = TypeKaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
