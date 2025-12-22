<?php

namespace App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
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
