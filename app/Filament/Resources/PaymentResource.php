<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables;
use App\Helpers\PembayaranStatus;
use App\Helpers\TransaksiStatus;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Components\Actions\Action as ActionEntry;
use Illuminate\Support\Facades\Request;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action as ActionTable;

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
                TextColumn::make('transaksi.customer.nama')
                    ->label('Customer'),
                TextColumn::make('transaksi.metode_pembayaran')
                    ->label('Metode pembayaran')->badge(),
                TextColumn::make('transaksi.jenis_transaksi')
                    ->label('Jenis transaksi')->badge(),
                TextColumn::make('transaksi.total_harga')
                    ->label('Total harga')->money('IDR', true),
                TextColumn::make('status')
                    ->label('Status pembayaran')->badge(),
                TextColumn::make('transaksi.status')
                    ->label('Status transaksi')->badge(),
                TextColumn::make('no_invoice')
                    ->label('No invoice'),
                ImageColumn::make('transfer.path')->disk('s3')
                    ->label('Bukti transfer'),

            ])->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfolistSection::make('Informasi')->columns(3)
                ->schema([
                    TextEntry::make('transaksi.kode_transaksi')
                        ->label('Kode transaksi'),
                    TextEntry::make('transaksi.metode_pembayaran')
                        ->label('Metode pembayaran')->badge(),
                    TextEntry::make('transaksi.jenis_transaksi')
                        ->label('Jenis transaksi')->badge(),
                    TextEntry::make('transaksi.total_harga')
                        ->label('Total harga')->money('IDR', true),
                    TextEntry::make('status')
                        ->label('Status pembayaran')->badge(),
                    TextEntry::make('transaksi.status')
                        ->label('Status transaksi')->badge(),
                    TextEntry::make('no_invoice')
                        ->label('No invoice'),
                ])
                ->footerActions([
                    ActionEntry::make('acc_kasir')
                        ->label('Konfirmasi')
                        ->icon(
                            'heroicon-o-check-circle'
                        )
                        ->color(
                            'success'
                        )
                        ->visible(fn($record) => $record->status === PembayaranStatus::MENUNGGU ? true : false)
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->status = PembayaranStatus::DITERIMA;
                            $record->save();
                            $record->transaksi->status = TransaksiStatus::ACC_KASIR;
                            $record->transaksi->save();
                        }),
                    ActionEntry::make('dikirim')
                        ->label('Dikirim')
                        ->icon(
                            'heroicon-o-check-circle'
                        )
                        ->visible(fn($record) => $record->status === PembayaranStatus::DITERIMA && TransaksiStatus::DIKIRIM ? false : true)
                        ->color(
                            'success'
                        )
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->status = PembayaranStatus::DITERIMA;
                            $record->save();
                            $record->transaksi->status = TransaksiStatus::DIKIRIM;
                            $record->transaksi->save();
                        }),
                    ActionEntry::make('ditolak')
                        ->label('Tolak')
                        ->visible(fn($record) => $record->status === PembayaranStatus::MENUNGGU ? true : false)
                        ->icon(
                            'heroicon-o-check-circle'
                        )
                        ->color(
                            'danger'
                        )
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->status = PembayaranStatus::DITOLAK;
                            $record->save();
                            $record->transaksi->status = TransaksiStatus::GAGAL;
                            $record->transaksi->save();
                        }),
                    ActionEntry::make('selesai')
                        ->label('Selesai')
                        ->visible(fn($record) => $record->status === PembayaranStatus::DITERIMA && $record->transaksi->status === TransaksiStatus::DIKIRIM && $record->transaksi->status != TransaksiStatus::SUKSES ? true : false)
                        ->icon(
                            'heroicon-o-check-circle'
                        )
                        ->color(
                            'success'
                        )
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->transaksi->status = TransaksiStatus::SUKSES;
                            $record->transaksi->save();
                        }),
                ]),
            InfolistSection::make('Bukti transfer')->columns(3)
                ->schema([
                    ImageEntry::make('transfer.path')
                        ->label('')
                        ->disk('s3')
                        ->height(300)
                ])
        ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
            'view' => Pages\ViewPayment::route('/{record}')
        ];
    }
}
