<?php

namespace App\Filament\Resources\StatsResourcesResource\Widgets;

use App\Models\Pendapatan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 3;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Omset', 'Rp ' . number_format(Pendapatan::sum('jumlah'), 0, ',', '.')),
            Stat::make('Keuntungan', 'Rp ' . number_format(Pendapatan::sum('keuntungan'), 0, ',', '.'))
                ->color('success')
                ->description('Total profit saat ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
