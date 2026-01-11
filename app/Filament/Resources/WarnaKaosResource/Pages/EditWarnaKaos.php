<?php

namespace App\Filament\Resources\WarnaKaosResource\Pages;

use App\Filament\Resources\WarnaKaosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarnaKaos extends EditRecord
{
    protected static string $resource = WarnaKaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
