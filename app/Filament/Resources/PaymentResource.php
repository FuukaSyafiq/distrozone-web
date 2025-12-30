<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Request;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class PaymentResource extends Resource
{
    protected static ?string $model = Pembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-s-document-duplicate';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function getBreadcrumb(): string
    {
        return '';
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaksi.kode_transaksi')
                    ->label('Kode transaksi'),
                TextColumn::make('transaksi.metode_pembayaran')
                    ->label('Metode pembayaran'),
                TextColumn::make('transaksi.jenis_transaksi')
                    ->label('Jenis transaksi'),
                TextColumn::make('transaksi.total_harga')
                    ->label('Total harga'),
                TextColumn::make('status')
                    ->label('Status pembayaran')->badge(),
            TextColumn::make('transaksi.status')
                ->label('Status transaksi')->badge(),
                TextColumn::make('no_invoice')
                    ->label('No invoice'),
                ImageColumn::make('transfer.path')->disk('s3')
                    ->label('Bukti transfer'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
