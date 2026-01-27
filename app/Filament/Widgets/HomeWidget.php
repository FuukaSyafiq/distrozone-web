<?php

namespace App\Filament\Widgets;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
class HomeWidget extends Widget
{
    use InteractsWithForms; // Gunakan trait ini

    protected static string $view = 'filament.widgets.home-widget';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    
    protected function getViewData(): array
    {
        return [
            'user' => Auth::user(),
        ];
    }
}
