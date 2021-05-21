<?php

namespace App\Providers;

use App\IntentHandlers\IntentHandlerFactory;
use App\IntentHandlers\IntentHandlerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            IntentHandlerService::class, function () {
                return new IntentHandlerService(new IntentHandlerFactory());
            }
        );
    }
}
