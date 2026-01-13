<?php

namespace App\Filament\Resources\KaosVarianResource\Pages;

use App\Filament\Resources\KaosVarianResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKaosVarian extends ViewRecord
{
	protected static string $resource = KaosVarianResource::class;

	protected function getHeaderActions(): array
	{
		return [
			Actions\EditAction::make(),
		];
	}
}
