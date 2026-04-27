<?php

namespace Sosupp\SlimerAccounting;

use Illuminate\Support\ServiceProvider;
use Sosupp\SlimerAccounting\Services\AccountingManager;
use Sosupp\SlimerAccounting\Services\TransactionBuilderService;

class AccountingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            TransactionBuilderService::class, 
            fn () => new TransactionBuilderService()
        );

        $this->app->singleton('accounting', function(){
            return new AccountingManager();
        });
    }

    public function boot()
    {

        if($this->app->runningInConsole()){
            $path = config('slimeronboarding.database.migration_path');
            $usePath = $path ? 'migrations/'.$path : 'migrations';
    
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path($usePath),
            ], 'slimer-accounting-migrations');
    
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('slimeraccounting.php'),
            ], 'slimer-accounting-config');

            $this->publishes([
                __DIR__.'/../../database/seeders/ChartOfAccountsSeeder.php' =>
                    database_path('seeders/ChartOfAccountsSeeder.php'),
            ], 'slimer-accounting-seeders');
        }
    }
}