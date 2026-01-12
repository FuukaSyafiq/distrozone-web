<?php

namespace App\Filament\Resources\UkuranKaosResource\Pages;

use App\Filament\Resources\UkuranKaosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUkuranKaos extends EditRecord
{
    protected static string $resource = UkuranKaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
