<?php

namespace Sosupp\SlimerAccounting\Services;

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
}