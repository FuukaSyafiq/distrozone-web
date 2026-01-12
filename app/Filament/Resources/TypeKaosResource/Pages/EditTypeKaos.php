<?php

namespace App\Filament\Resources\TypeKaosResource\Pages;

use App\Filament\Resources\TypeKaosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeKaos extends EditRecord
{
    protected static string $resource = TypeKaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
