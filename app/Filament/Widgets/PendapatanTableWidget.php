<?php

namespace App\Filament\Widgets;

use App\Models\Pendapatan;
use Filament\Tables;
use  Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Log;

class PendapatanTableWidget extends BaseWidget
{

    use InteractsWithPageFilters;

    protected static ?int $sort = 3; // Agar muncul di bawah Chart
    protected int | string | array $columnSpan = 'full'; // Agar lebar penuh

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $filters = $this->filters;

                Log::info($this->filters['startDate'] ?? 'tidak ada tanggal mulai');
                return Pendapatan::query()
                    // Filter Tanggal Mulai (jika diisi)
                    ->when(
                        $filters['startDate'] ?? null,
                        fn($q, $date) => $q->whereDate('tanggal', '>=', $date)
                    )
                    // Filter Tanggal Selesai (jika diisi)
                    ->when(
                        $filters['endDate'] ?? null,
                        fn($q, $date) => $q->whereDate('tanggal', '<=', $date)
                    )
                    ->when($filters['jenis'] ?? null, fn($q, $jenis) => $q->where('jenis', $jenis))
                    ->when($filters['kasir'] ?? null, function ($q, $kasir) {
                        $q->whereHas('transaksi.kasir', function ($query) use ($kasir) {
                            // Ganti 'name' sesuai kolom nama di tabel users Anda
                            $query->where('nama', $kasir);
                        });
                    });
            })
            ->columns([
                TextColumn::make('transaksi.metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->badge(),
                TextColumn::make('transaksi.kasir.nama')
                    ->label('Kasir '),
                TextColumn::make('jenis')
                    ->label('Jenis Pendapatan')
                    ->badge()->color(fn(string $state): string => match ($state) {
                        'OFFLINE' => 'danger',  // Warna Merah
                        'ONLINE' => 'success',  // Warna Hijau
                        default => 'gray',      // Warna default jika tidak cocok
                    }),

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
            ])->bulkActions([
            BulkAction::make('cetak')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('success') // Hijau biasanya lebih cocok untuk cetak daripada merah (danger)
                ->action(function (Collection $records) {
                    $ids = $records->pluck('id')->toArray();

                    // Ambil tanggal dari kolom 'tanggal', bukan 'created_at' agar konsisten dengan filter
                    $startDate = $records->min('tanggal');
                    $endDate   = $records->max('tanggal');

                    // Generate URL
                    $url = route('pendapatan.cetak.pdf', [
                        'ids'        => implode(',', $ids),
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                    ]);

                    // Gunakan emit/dispatch atau redirect lewat browser
                    $this->redirect($url);
                }),
        ]);
    }
}
