<?php

namespace App\Filament\Resources\KasirResource\Pages;

use App\Filament\Resources\KasirResource;
use App\Helpers\NikVerified;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Models\Role;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Hash;

class CreateKasir extends CreateRecord
{
    protected static string $resource = KasirResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nik_verified'] = NikVerified::APPROVED;
        $data['role_id'] = Role::getIdByRole('KASIR');
        $data['password'] = Hash::make($data['password']);
     
        return $data;
    }
}
