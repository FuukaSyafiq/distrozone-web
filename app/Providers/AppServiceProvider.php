<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\KeranjangDetail;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $cartCount = KeranjangDetail::whereHas('keranjang', function ($q) {
                    $q->where('id_customer', auth()->id())
                        ->where('status', 'AKTIF');
                })->count();

                $view->with('cartCount', $cartCount);
            }
        });
    }
}
