<?php

namespace App\Filament\Resources\KaosResource\Pages;

use App\Filament\Resources\KaosResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Illuminate\Http\File;

class CreateKaos extends CreateRecord
{
    protected static string $resource = KaosResource::class;

}
