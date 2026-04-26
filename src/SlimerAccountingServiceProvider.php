<?php

namespace Sosupp\SlimerAccounting;

use Illuminate\Support\ServiceProvider;
use Sosupp\SlimerAccounting\Services\TransactionBuilderService;

class AccountingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            TransactionBuilderService::class, 
            fn () => new TransactionBuilderService()
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'slimer-accounting-migrations');

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('slimeraccounting.php'),
        ], 'slimer-accounting-config');
    }
}