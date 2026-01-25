<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Illuminate\Support\Facades\Session;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as SectionEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action as ActionEntry;
use App\Models\Image;
use App\Models\KeranjangDetail;
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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use App\Helpers\TransaksiStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\RepeatableEntry;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaksi::class;

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
                TextColumn::make('kode_transaksi')
                    ->label('Kode transaksi'),
                TextColumn::make('customer.nama')
                    ->label('Customer'),
                TextColumn::make('kasir.nama')
                    ->label('Kasir'),
                TextColumn::make('metode_pembayaran')
                    ->label('Metode pembayaran'),
                TextColumn::make('status')
                    ->label('Status')->badge(),
                TextColumn::make('jenis_transaksi')
                    ->label('Jenis transaksi')->badge(),
                TextColumn::make('ongkir')
                    ->label('Ongkir')->money('IDR', true),
                TextColumn::make('total_harga')
                    ->label('Total harga')->money('IDR', true),
                TextColumn::make('expires_at')
                    ->label('Tanggal expired')->date('d M Y, H:i')->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')->date('d M Y'),
            ])
            ->filters([
                SelectFilter::make('status_transaksi')->options([
                    'PENDING' => TransaksiStatus::PENDING,
                    'DIKIRIM' => TransaksiStatus::DIKIRIM,
                    'SUKSES' => TransaksiStatus::SUKSES,
                    'GAGAL' => TransaksiStatus::GAGAL,
                ])->query(function (Builder $query, array $data) {
                    if (! $data['value']) {
                        return;
                    }

                    $query->where('status', $data['value']);
                })
            ])
            ->actions([
                ViewAction::make(),
                Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('danger')
                    ->url(fn($record) => route('transaksi.cetak.pdf', [
                        'id'        => $record->id_transaksi,
                        'date' => $record->created_at->toDateString()
                    ]))->openUrlInNewTab()
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
                        TextEntry::make('kode_transaksi')
                            ->label('Kode transaksi'),
                        TextEntry::make('jenis_transaksi')
                            ->label('Jenis transaksi')->badge(),
                        TextEntry::make('metode_pembayaran')
                            ->label('Metode pembayaran')->badge(),
                        TextEntry::make('total_harga')
                            ->label('Total harga')->money('IDR', true),
                        TextEntry::make('ongkir')
                            ->label('Ongkir')->money('IDR', true),
                        TextEntry::make('ongkir.tarif_per_kg')
                            ->label('Tarif ongkir / kg')->money('IDR', true),
                        TextEntry::make('status')
                            ->label('Status transaksi')->badge(),
                        TextEntry::make('kasir.nama')
                            ->label('Kasir'),


                    ])->footerActions([
                        ActionEntry::make('selesai')
                            ->label('Selesai')
                            ->visible(fn($record) => $record->status === TransaksiStatus::DIKIRIM)
                            ->icon(
                                'heroicon-o-check-circle'
                            )
                            ->color(
                                'success'
                            )
                            ->requiresConfirmation()
                            ->action(function ($record) {
                                foreach ($record->details as $d) {
                                    KeranjangDetail::where('id_kaos_varian', $d->id_kaos_varian)->delete();
                                }
                                $record->status = TransaksiStatus::SUKSES;
                                $record->save();
                                // Mail::to($record->transaksi->customer->email)
                                //     ->send(new MengirimAnnounce($record->transaksi));
                            }),
                        ActionEntry::make('cetak')
                            ->label('Cetak')
                            ->icon('heroicon-o-printer')
                            ->color('danger')
                            ->url(fn($record) => route('transaksi.cetak.pdf', [
                                'id'        => $record->id_transaksi,
                                'date' => $record->created_at->toDateString()
                            ]))->openUrlInNewTab()
                    ]),
                SectionEntry::make('Customer')->columns(2)
                    ->schema([
                        TextEntry::make('customer.nama')
                            ->label('Customer'),
                        TextEntry::make('customer.no_telepon')
                            ->label('No telepon'),
                        TextEntry::make('customer.kota.kota')
                            ->label('Kota'),
                        TextEntry::make('customer.kota.provinsi.provinsi')
                            ->label('Provinsi'),
                        TextEntry::make('customer.alamat_lengkap')
                            ->label('Alamat'),
                        TextEntry::make('customer.nik')
                            ->label('NIK'),
                        TextEntry::make('customer.email')
                            ->label('Email'),
                    ]),

                RepeatableEntry::make('details')
                    ->label('Daftar Kaos')->columnSpanFull()->columns(2)
                    ->schema([
                        TextEntry::make('kaos_varian.kaos.nama_kaos')
                            ->label('Kaos'),

                        TextEntry::make('kaos_varian.kaos.merek.merek')
                            ->label('Merek kaos'),

                        TextEntry::make('kaos_varian.kaos.type.type')
                            ->label('Type kaos')
                            ->badge(),

                        TextEntry::make('kaos_varian.warna.label')
                            ->label('Warna kaos')
                            ->badge(),

                        TextEntry::make('kaos_varian.ukuran.ukuran')
                            ->label('Ukuran kaos')
                            ->badge(),

                        TextEntry::make('kaos_varian.harga_jual')
                            ->label('Harga jual')
                            ->money('IDR', true),

                        TextEntry::make('kaos_varian.stok_kaos')
                            ->label('Stok kaos'),

                        ImageEntry::make('kaos_varian.image_path')->label('Foto kaos')
                            ->disk('s3')
                            ->height(100),
                    ]),
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
