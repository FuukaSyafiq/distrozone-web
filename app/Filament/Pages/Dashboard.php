<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm; // Menambahkan kemampuan filter pada halaman dashboard

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Filter tanggal')
                ->schema([
                    Grid::make(4)
                        ->schema([
                            DatePicker::make('startDate')
                                ->label('Tanggal Mulai')
                                ->live() // Agar widget langsung merespon saat tanggal dipilih
                                ->native(false)
                                ->displayFormat('d M Y'),

                            DatePicker::make('endDate')
                                ->label('Tanggal Selesai')
                                ->live() // Agar widget langsung merespon saat tanggal dipilih
                                ->native(false)
                                ->displayFormat('d M Y'),
                            Select::make('jenis')
                                ->options([
                                    'ONLINE' => 'ONLINE',
                                    'OFFLINE' => 'OFFLINE',
                                ])->placeholder('SEMUA'),
                            Select::make('kasir')
                                ->options(
                                    User::query()
                                        ->pluck('nama', 'nama') // Menampilkan Nama, menyimpan Nama ke filter
                                        ->toArray()
                                )
                                ->searchable() // Tambahkan ini agar mudah mencari jika user banyak
                                ->placeholder('SEMUA KASIR')
                                ->live()
                                ->native(false),
                        ])
                ])->collapsible(),
        ]);
    }
}
