<?php

namespace App\Filament\Resources\KaosResource\Pages;

use App\Filament\Resources\KaosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
class EditKaos extends EditRecord
{
    protected static string $resource = KaosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        $newPaths = $data['foto_kaos'] ?? [];

        // ambil foto lama
        $oldImages = Image::where('id_kaos', $record->id_kaos)->get();
        $oldPaths  = $oldImages->pluck('path')->toArray();

        /**
         * 1️⃣ HAPUS FOTO YANG DIHILANGKAN
         */
        $pathsToDelete = array_diff($oldPaths, $newPaths);

        foreach ($pathsToDelete as $path) {
            Storage::disk('s3')->delete($path);
            Image::where('id_kaos', $record->id_kaos)
                ->where('path', $path)
                ->delete();
        }

        /**
         * 2️⃣ TAMBAH FOTO BARU
         */
        foreach ($newPaths as $path) {
            Image::firstOrCreate([
                'id_kaos' => $record->id_kaos,
                'path'    => $path,
            ], [
                'file_name' => basename($path),
            ]);
        }

        return $record;
    }



    // protected function afterSave()
    // {
    //     $data = $this->data; // data form
    //     $record = $this->record; // sudah ada ID
    //     if (empty($data['foto_kaos'])) {
    //         $images = Image::where('id_kaos', $record->id_kaos)->get();
    //         foreach($images as $image) {
    //             Storage::disk('s3')->delete($image->path);
    //             $image->delete();
    //         }
    //         return;
    //     }

    //     if (isset($data['foto_kaos'])) {
    //         foreach ($data['foto_kaos'] as $kaos) {
    //             $localPath = $kaos;
    //             try {
    //                 $findPrevImage = Image::where('path', $localPath)->first();
    //                 if ($findPrevImage != null) {
    //                     continue;
    //                 }

    //                 $absPath = Storage::disk('local')->path($localPath);
    //                 dd([
    //                     'localPath' => $localPath,
    //                     'is_absolute' => str_starts_with($localPath, '/'),
    //                     'storage_local_exists' => Storage::disk('local')->exists($localPath),
    //                     'absPath' => Storage::disk('local')->path($localPath),
    //                     'php_exists' => file_exists($localPath),
    //                 ]);
    //                 if (!file_exists($absPath) || !is_readable($absPath)) {
    //                     throw new \Exception("File lokal tidak ditemukan: $absPath");
    //                 }
    //                 dd("You lolos");
    //                 // pindahkan ke S3
    //                 $s3Path = Storage::disk('s3')->put(
    //                     'kaos',
    //                     new File($absPath)
    //                 );

    //                 Image::create([
    //                     "path" =>  $s3Path,
    //                     "file_name" => basename($localPath),
    //                     "mime_type" =>  mime_content_type($absPath),
    //                     "size" => filesize($absPath),
    //                     "id_kaos" => $record->id_kaos,
    //                 ]);
    //             } finally {
    //                 Storage::disk('local')->delete($localPath);
    //             }
    //         }
    //     }

    // }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        
        $images = Image::where('id_kaos', $data['id_kaos'])->get();
        // dd($images);
        $data['foto_kaos'] = $images->pluck('path')->toArray();
        return $data;
    }
}
