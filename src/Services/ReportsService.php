<?php

namespace Sosupp\SlimerAccounting\Services;

class ReportsService
{
    public function trialBalance($from = null, $to = null)
    {
        return app(TrialBalance::class)->generate($from, $to);
    }

    public function profitAndLoss($from = null, $to = null)
    {
        return app(ProfitAndLoss::class)->generate($from, $to);
    }

    public function balanceSheet($asAt = null)
    {
        return app(BalanceSheet::class)->generate($asAt);
    }
}