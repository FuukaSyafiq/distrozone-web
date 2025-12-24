<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Helpers\DeleteImages;
use App\Helpers\StoreImages;
use App\Filament\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
  
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount($record): void
    {
        parent::mount($record);
    }
}
