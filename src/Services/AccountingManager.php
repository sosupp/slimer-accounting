<?php

namespace Sosupp\SlimerAccounting\Services;

use Sosupp\SlimerAccounting\Services\Dashboard\DashboardService;

class AccountingManager
{
    public function transaction()
    {
        return app(TransactionBuilderService::class);
    }

    public function reports()
    {
        return app(ReportsService::class);
    }

    public function dashboard()
    {
        return app(DashboardService::class);
    }

    public function trialBalance()
    {
        return app(TrialBalanceService::class);
    }
}
