<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
class HomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.home-widget';
    protected function getViewData(): array
    {
        return [
            'user' => Auth::user(),
        ];
    }
}
