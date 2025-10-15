<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Observers\OrderItemObserver;
use App\Models\OrderItem;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        OrderItem::observe(OrderItemObserver::class);

        $this->loadApiRoutes();
    }

    protected function loadApiRoutes(): void
    {
        $apiRoutesPath = base_path('routes/api.php');

        if (file_exists($apiRoutesPath)) {
            Route::prefix('api')
                ->middleware('api')
                ->group($apiRoutesPath);
        }
    }
}
