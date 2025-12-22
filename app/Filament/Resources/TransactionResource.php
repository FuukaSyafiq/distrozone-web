<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Illuminate\Support\Facades\Session;

use App\Models\Image;
use App\Models\Role;
use App\Models\Tagihan;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class TransactionResource extends Resource
{
    protected static ?string $model = VerifikasiPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-c-circle-stack';
    protected static ?string $navigationLabel = 'Transaction history';

    protected static ?string $navigationGroup = 'Financial Transactions';

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
        return $table->modifyQueryUsing(function ($query) {
            if (auth()->user()->role_id === Role::getIdByRole("PENYEWA")) {
                return $query->where('pengirim', auth()->user()->name)->where('is_valid', true);
            }
            return $query->where('is_valid', true);
        })
            ->columns([
                TextColumn::make('amount')->label('Nominal')->formatStateUsing(fn($state) => 'Rp. ' . number_format($state, 0, ',', '.')),
                TextColumn::make('pengirim')->label('pengirim'),
                TextColumn::make('no_invoice')->label('No Invoice'),
                TextColumn::make('updated_at')->label('Tanggal diverifikasi')->formatStateUsing(fn($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                TextColumn::make('tanggal_dibayar')->label('Tanggal dibayar')->formatStateUsing(fn($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                ImageColumn::make('bukti_file.path')
                    ->label('Invoice')->getStateUsing(callback: function ($record) {
                        // dd($record->id);
                        $invoice = VerifikasiPembayaran::where('id', $record->id)->first();
                        $image = Image::where('id', $invoice->bukti_file)->first();
                        // Debugging untuk melihat nilai yang didapat
                        return isset($image->path) ? url($image->path) : "";
                    }),

            ])
            ->filters([
                // SelectFilter::make('')
            ])
            ->actions([
                Action::make('Cetak kuitansi')
                    ->url(fn(VerifikasiPembayaran $record) => route('buktibayar.settled.pdf', $record))
                    ->openUrlInNewTab()
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
