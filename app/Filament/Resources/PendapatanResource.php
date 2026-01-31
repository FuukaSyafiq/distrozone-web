<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendapatanResource\Pages;
use App\Filament\Resources\PendapatanResource\RelationManagers;
use App\Models\Pendapatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action as ActionTable;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendapatanResource extends Resource
{
    protected static ?string $model = Pendapatan::class;

    protected static ?string $navigationIcon = 'heroicon-s-chart-bar';
    protected static ?string $navigationLabel = 'Pendapatan';
    protected static ?string $navigationGroup = 'Toko';


    public static function canCreate(): bool
    {
        return false;
    }
    public static function canAccess(): bool
    {
        return false;
    }
    public static function getBreadcrumb(): string
    {
        return '';
    }
    public static function canView(Model $record): bool
    {
        return false;
    }

    public static function canEdit(Model $model): bool
    {
        return false;
    }
    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaksi.metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->badge(),

                TextColumn::make('jenis')
                    ->label('Jenis Pendapatan')
                    ->badge(),

                TextColumn::make('jumlah')
                    ->label('Omset')
                    ->money('IDR'),

                TextColumn::make('modal')
                    ->label('Total Harga Pokok')
                    ->money('IDR'),
                TextColumn::make('transaksi.ongkir')
                    ->label('Ongkir')
                    ->money('IDR'),
                TextColumn::make('keuntungan')
                    ->label('Keuntungan')
                    ->money('IDR'),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y'),
            ])
            ->filters([
                SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options([
                        1  => 'Januari',
                        2  => 'Februari',
                        3  => 'Maret',
                        4  => 'April',
                        5  => 'Mei',
                        6  => 'Juni',
                        7  => 'Juli',
                        8  => 'Agustus',
                        9  => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->whereMonth('created_at', $data['value']);
                        }
                    }),
                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(
                        collect(range(now()->year, now()->year - 5))
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray()
                    )
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->whereYear('created_at', $data['value']);
                        }
                    }),
            ])
            ->actions([])
            ->bulkActions([
                BulkAction::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('danger')
                    ->action(function (Collection $records) {
                        $ids = $records->pluck('id')->implode(',');

                        $startDate = $records->min('created_at')->toDateString();
                        $endDate   = $records->max('created_at')->toDateString();

                        return redirect()->route('pendapatan.cetak.pdf', [
                            'ids'        => $ids,
                            'start_date' => $startDate,
                            'end_date'  => $endDate,
                        ]);
                    }),

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
            'index' => Pages\ListPendapatans::route('/'),
            'create' => Pages\CreatePendapatan::route('/create'),
            'view' => Pages\ViewPendapatan::route('/{record}'),
            'edit' => Pages\EditPendapatan::route('/{record}/edit'),
        ];
    }
}
