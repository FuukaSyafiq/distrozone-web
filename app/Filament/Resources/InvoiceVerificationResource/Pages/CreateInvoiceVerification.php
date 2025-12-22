<?php

namespace App\Filament\Resources\InvoiceVerificationResource\Pages;

use App\Filament\Resources\InvoiceVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoiceVerification extends CreateRecord
{
    protected static string $resource = InvoiceVerificationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
