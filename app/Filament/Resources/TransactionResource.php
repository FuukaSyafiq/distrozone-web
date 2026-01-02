<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Illuminate\Support\Facades\Session;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as SectionEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Models\Image;
use App\Models\Role;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\VerifikasiPembayaran;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class TransactionResource extends Resource
{
    protected static ?string $model = TransaksiDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function getBreadcrumb(): string
    {
        return '';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    // Check if the user can edit
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    // Check if the user can delete
    public static function canDeleteAny(): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaksi.kode_transaksi')
                    ->label('Kode transaksi'),
                TextColumn::make('kaos.nama_kaos')
                    ->label('Kaos'),
                TextColumn::make('transaksi.metode_pembayaran')
                    ->label('Metode pembayaran'),
                TextColumn::make('transaksi.status')
                    ->label('Status')->badge(),
                TextColumn::make('qty')
                    ->label('Quantity'),
                TextColumn::make('harga_satuan')
                    ->label('Harga satuan')->money('IDR', true),
                TextColumn::make('subtotal')
                    ->label('Subtotal')->money('IDR', true),
            ])
            ->filters([
                // SelectFilter::make('')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                SectionEntry::make('Detail')->columns(3)
                    ->schema([
                        TextEntry::make('transaksi.kode_transaksi')
                            ->label('Kode transaksi'),
                        TextEntry::make('transaksi.jenis_transaksi')
                            ->label('Jenis transaksi'),
                        TextEntry::make('transaksi.metode_pembayaran')
                            ->label('Metode pembayaran'),
                        TextEntry::make('transaksi.total_harga')
                            ->label('Total harga')->money('IDR', true),
                        TextEntry::make('transaksi.ongkir')
                            ->label('Ongkir')->money('IDR', true),
                        TextEntry::make('transaksi.ongkir.tarif_per_kg')
                            ->label('Tarif ongkir / kg')->money('IDR', true),
                        TextEntry::make('transaksi.status')
                            ->label('Status transaksi'),
                        TextEntry::make('transaksi.kasir.nama')
                            ->label('Kasir'),


                    ]),
                SectionEntry::make('Customer')->columns(2)
                    ->schema([
                        TextEntry::make('transaksi.customer.nama')
                            ->label('Customer'),
                        TextEntry::make('transaksi.customer.no_telepon')
                            ->label('No telepon'),
                        TextEntry::make('transaksi.customer.alamat')
                            ->label('Alamat'),
                        TextEntry::make('transaksi.customer.nik')
                            ->label('NIK'),
                        TextEntry::make('transaksi.customer.email')
                            ->label('Email'),
                    ]),
                SectionEntry::make('Kaos')->columns(2)
                    ->schema([
                        TextEntry::make('kaos.nama_kaos')
                            ->label('Kaos'),
                        TextEntry::make('kaos.merek_kaos')
                            ->label('Merek kaos'),
                        TextEntry::make('kaos.type_kaos')
                            ->label('Type kaos'),
                        TextEntry::make('kaos.warna_kaos')
                            ->label('Warna kaos'),
                        TextEntry::make('kaos.ukuran')
                            ->label('Ukuran kaos'),
                        TextEntry::make('kaos.harga_jual')
                            ->label('Harga jual')->money('IDR', true),
                        TextEntry::make('kaos.stok_kaos')
                            ->label('Stok kaos'),
                    ]),
                SectionEntry::make('Foto kaos')->columns(2)
                    ->schema([
                        ImageEntry::make('customer_image_by_kaos')->label("")
                            ->disk('s3')
                            ->height(300),
                    ])
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
            'view' => Pages\ViewTransaction::route('/{record}')
        ];
    }
}
