<?php

namespace Sosupp\SlimerAccounting\Console;

use Illuminate\Console\Command;

class AccountingInstall extends Command 
{
    
    protected $signature = 'slimer:accounting-install';

    protected $description = 'Add business accounting to your app';

    public function handle(): int
    {
        $this->info('Adding necessary files and classes');

        $this->installTasksData();

        return 1;
    }


    private function installTasksData()
    {
        $this->info('Adding migration files');

        $this->call('vendor:publish', [
            '--tag' => 'slimer-accounting-migrations'
        ]);

    }
}