<?php

namespace App\Filament\Resources\MerekKaosResource\Pages;

use App\Filament\Resources\MerekKaosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMerekKaos extends EditRecord
{
    protected static string $resource = MerekKaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
