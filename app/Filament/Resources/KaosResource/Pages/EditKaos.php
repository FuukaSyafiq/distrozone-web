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

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     $record->update($data);

    //     $newPaths = $data['foto_kaos'] ?? [];

    //     // ambil foto lama
    //     $oldImages = Image::where('id_kaos', $record->id_kaos)->get();
    //     $oldPaths  = $oldImages->pluck('path')->toArray();

    //     /**
    //      * 1️⃣ HAPUS FOTO YANG DIHILANGKAN
    //      */
    //     $pathsToDelete = array_diff($oldPaths, $newPaths);

    //     foreach ($pathsToDelete as $path) {
    //         Storage::disk('s3')->delete($path);
    //         Image::where('id_kaos', $record->id_kaos)
    //             ->where('path', $path)
    //             ->delete();
    //     }

    //     /**
    //      * 2️⃣ TAMBAH FOTO BARU
    //      */
    //     foreach ($newPaths as $path) {
    //         Image::firstOrCreate([
    //             'id_kaos' => $record->id_kaos,
    //             'path'    => $path,
    //         ], [
    //             'file_name' => basename($path),
    //         ]);
    //     }

    //     return $record;
    // }

}
