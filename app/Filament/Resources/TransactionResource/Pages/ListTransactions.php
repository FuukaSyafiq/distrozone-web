<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\VerifikasiPembayaran;
use App\Models\Role;
use Filament\Facades\Filament;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Tables\Actions\ModalAction;


class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return 'Transaksi';
    }
}
