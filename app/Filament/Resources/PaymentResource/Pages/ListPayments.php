<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use App\Models\Role;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Log;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
