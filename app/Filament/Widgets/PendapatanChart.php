<?php

namespace App\Filament\Widgets;

use App\Models\Pendapatan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema; // Pastikan import ini
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema; // Wajib pakai trait ini
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PendapatanChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Analisis Pendapatan';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;


    protected function getData(): array
    {
        $filters = $this->filters;

        // Tentukan rentang waktu (default 30 hari terakhir jika filter kosong)
        $start = $filters['startDate'] ? Carbon::parse($filters['startDate']) : now()->startOfMonth();
        $end = $filters['endDate'] ? Carbon::parse($filters['endDate']) : now()->endOfMonth();

        // Query dasar
        $query = Pendapatan::query()
            ->when($filters['jenis'] ?? null, fn($q, $jenis) => $q->where('jenis', $jenis));

        if ($filters['kasir'] ?? null) {
            $query =   $query->whereHas('transaksi.kasir', function ($q) use ($filters) {
                $q->where('nama', $filters['kasir']);
            });
        }

        // Kita ambil data per hari menggunakan Trend (atau query manual)
        // Di sini saya pakai query manual agar kamu tidak perlu install package tambahan
        $data = $query->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->tanggal)->format('d M'))
            ->map(fn($items) => [
                'omset' => $items->sum('jumlah'),
                'keuntungan' => $items->sum('keuntungan'),
            ]);

        return [
            'datasets' => [
                [
                    'label' => 'Total Omset',
                    'data' => $data->pluck('omset')->toArray(),
                    'backgroundColor' => '#6366f1',
                    'borderColor' => '#6366f1',
                ],
                [
                    'label' => 'Total Keuntungan',
                    'data' => $data->pluck('keuntungan')->toArray(),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
