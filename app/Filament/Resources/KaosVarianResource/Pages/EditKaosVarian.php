<?php

namespace App\Filament\Resources\KaosVarianResource\Pages;

use App\Filament\Resources\KaosVarianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKaosVarian extends EditRecord
{
    protected static string $resource = KaosVarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
