<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Helpers\StoreImages;
use App\Models\Image;
use App\Models\Role;
use Filament\Actions;
use \Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Buat'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }


    public function handleRecordCreation(array $data): Model
    {
        try {
            DB::beginTransaction();
            $ktp = StoreImages::StoreImages($data['ktp_id']);

	
            $record = static::getModel()::create([
                "name" => $data['name'],
                "address" => $data['address'],
                "contact" => $data['contact'],
                "email" => $data['email'],
                "password" => Hash::make($data['password']),
                "role_id" => Role::getIdByRole("PENYEWA"),
                "ktp_id" => $ktp->id
            ]);

            DB::commit();

            $record->save();
            return $record;
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Log the error message
            Log::error('Error creating DataPendaftar: ' . $e->getMessage());

            // Rethrow the exception or handle it as needed
            throw $e;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        $userId = auth()->user()->id;
        $userRole = auth()->user()->role_id;

        if($userRole === Role::getIdbyRole('PENYEWA')) {

            // Redirect dynamically to the appropriate URL
            redirect("/penyewa/users/{$userId}");
            // redirect("/{$roleName}/payments/create");
        }
    }
}
