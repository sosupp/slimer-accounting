<?php

namespace Sosupp\SlimerAccounting\Services\Dashboard;

class DashboardService
{
    public function overview($from = null, $to = null)
    {
        return app(Overview::class)->generate($from, $to);
    }

    public function cashFlow($from = null, $to = null)
    {
        return app(CashFlow::class)->generate($from, $to);
    }

    public function revenueTrend($from = null, $to = null)
    {
        return app(RevenueTrend::class)->generate($from, $to);
    }
}
