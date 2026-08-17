<?php

namespace Sosupp\SlimerAccounting\Services\Transactions;

use Sosupp\SlimerAccounting\Services\TransactionBuilderService;

class Income
{
    public function handle(array $data)
    {
        return app(TransactionBuilderService::class)
            ->create($data)
            ->debit(
                accountId: $data['payment_account_id'],
                amount: $data['amount']
            )
            ->credit(
                accountId: $data['category_account_id'],
                amount: $data['amount']
            )
            ->post();
    }
}
