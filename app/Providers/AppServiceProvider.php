<?php

namespace App\Providers;

use App\IntentHandlers\IntentHandlerFactory;
use App\IntentHandlers\IntentHandlerService;
use App\IntentHandlers\WikipediaIntentHandler;
use App\SearchQuery\Builders\AudioQueryBuilder;
use App\SearchQuery\Builders\QueryBuilder;
use App\SearchQuery\Handlers\WikipediaQueryHandler;
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

        $this->app->singleton(
            QueryBuilder::class, function(): QueryBuilder {
                return new AudioQueryBuilder(
                    config('audio.recorder'),
                    config('audio.args'),
                    config('audio.fileFormat'),
                    config('audio.timeoutSecs')
                );
            }
        );

        $this->app->singleton(
            WikipediaIntentHandler::class, function($app): WikipediaIntentHandler {
                return new WikipediaIntentHandler(
                    $app->make(QueryBuilder::class),
                    $app->make(WikipediaQueryHandler::class)
                );
            }
        );
    }
}
