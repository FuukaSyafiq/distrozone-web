<?php

namespace App\Filament\Resources\KasirResource\Pages;

use App\Filament\Resources\KasirResource;
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
        $data['verified'] = true;
        $data['role_id'] = Role::getIdByRole('KASIR');
        $data['password'] = Hash::make($data['password']);
        if (isset($data['foto_customer'])) {
            $localPath = $data['foto_customer'];
            try {

                $absPath = Storage::disk('local')->path($localPath);

                if (!file_exists($absPath) || !is_readable($absPath)) {
                    throw new \Exception("File lokal tidak ditemukan: $absPath");
                }
                // pindahkan ke S3
                $s3Path = Storage::disk('s3')->put(
                    'foto_customers',
                    new File($absPath)
                );
                if (! $s3Path) {
                    throw new \Exception('Gagal upload file ke S3');
                }
                $image = Image::create([
                    "path" =>  $s3Path,
                    "file_name" => basename($localPath),
                    "mime_type" =>  mime_content_type($absPath),
                    "size" => filesize($absPath),
                ]);

                $data['foto_id'] =  $image->id;

            } catch (\Throwable $err) {
                Storage::disk('local')->delete($localPath);
            } finally {
                Storage::disk('local')->delete($localPath);
            }
        }

        return $data;
    }
}
