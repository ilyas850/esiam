<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);       

        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression, 0, ',', '.'); ?>";
        });

        config(['app.locale' => 'id']);
        setlocale(LC_ALL, 'IND');
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        // Carbon::now()->formatLocalized("A, %d %B %Y");
    }
}
