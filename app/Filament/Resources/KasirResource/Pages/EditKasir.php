<?php

namespace App\Filament\Resources\KasirResource\Pages;

use App\Filament\Resources\KasirResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class EditKasir extends EditRecord
{
    protected static string $resource = KasirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (filled($data['password'] ?? null)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // WAJIB
        }

        if (!filled($data['foto_karyawan'] ?? null)) {
            unset($data['foto_karyawan']); // biarkan foto lama
            return $data;
        }


        $localPath = $data['foto_karyawan'];
        $absPath = Storage::disk('local')->path($localPath);

        DB::transaction(function () use ($absPath, $localPath, &$data) {
            // upload baru
            $s3Path = Storage::disk('s3')->put(
                'foto_karyawan',
                new File($absPath)
            );
        
            $image = Image::create([
                'path' => $s3Path,
                'file_name' => basename($localPath),
                'mime_type' => mime_content_type($absPath),
                'size' => filesize($absPath),
            ]);

            $oldImage = $this->record->image;

            // hapus record image
            if ($oldImage) {
                Storage::disk('s3')->delete($oldImage->path);
                $oldImage->delete();
            }

            $data['foto_id'] = $image->id;

        });
        return $data;
    }
}
