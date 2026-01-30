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
        // Pastikan $filters tidak null, jika null jadikan array kosong
        $filters = $this->filters ?? [];

        // Gunakan null coalescing (??) untuk menghindari "Trying to access array offset on null"
        $startDate = $filters['startDate'] ?? null;
        $endDate = $filters['endDate'] ?? null;

        // Tentukan rentang waktu aman
        $start = $startDate ? Carbon::parse($startDate) : now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : now()->endOfMonth();

        // Query dasar
        $query = Pendapatan::query()
            ->when($filters['jenis'] ?? null, fn($q, $jenis) => $q->where('jenis', $jenis));

        // Gunakan helper optional atau check isset untuk kasir
        if (isset($filters['kasir']) && $filters['kasir']) {
            $query->whereHas('transaksi.kasir', function ($q) use ($filters) {
                $q->where('nama', $filters['kasir']);
            });
        }

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
