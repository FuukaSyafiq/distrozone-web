<?php

namespace App\Filament\Resources\KaosResource\Pages;

use App\Filament\Resources\KaosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
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

    protected function afterSave()
    {
        $data = $this->data; // data form
        $record = $this->record; // sudah ada ID
        if (empty($data['foto_kaos'])) {
            return;
        }
        if (isset($data['foto_kaos'])) {
            foreach ($data['foto_kaos'] as $kaos) {
                $localPath = $kaos;
                try {

                    $absPath = Storage::disk('local')->path($localPath);

                    if (!file_exists($absPath) || !is_readable($absPath)) {
                        throw new \Exception("File lokal tidak ditemukan: $absPath");
                    }
                    // pindahkan ke S3
                    $s3Path = Storage::disk('s3')->put(
                        'kaos',
                        new File($absPath)
                    );


                    Image::create([
                        "path" =>  $s3Path,
                        "file_name" => basename($localPath),
                        "mime_type" =>  mime_content_type($absPath),
                        "size" => filesize($absPath),
                        "id_kaos" => $record->id_kaos,
                    ]);
                } catch (\Throwable $err) {
                    Storage::disk('local')->delete($localPath);
                } finally {
                    Storage::disk('local')->delete($localPath);
                }
            }
        }

    }
}
