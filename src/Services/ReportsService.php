<?php

namespace Sosupp\SlimerAccounting\Services;

class ReportsService
{
    public function trialBalance($from = null, $to = null)
    {
        return app(TrialBalanceService::class)->generateV2($from, $to);
    }

    public function profitAndLoss($from = null, $to = null)
    {
        return app(ProfitAndLossService::class)->generate($from, $to);
    }

    public function balanceSheet($asAt = null)
    {
        return app(BalanceSheetService::class)->generate($asAt);
    }
}