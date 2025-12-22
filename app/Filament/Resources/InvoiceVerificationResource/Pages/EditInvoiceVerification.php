<?php

namespace App\Filament\Resources\InvoiceVerificationResource\Pages;

use App\Filament\Resources\InvoiceVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoiceVerification extends EditRecord
{
    protected static string $resource = InvoiceVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
