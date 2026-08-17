<?php

namespace Sosupp\SlimerAccounting\Services\Transactions;

use Sosupp\SlimerAccounting\Services\TransactionBuilderService;

class Expense
{
    public function handle(array $data)
    {
        return app(TransactionBuilderService::class)
            ->create($data)
            ->debit(
                accountId: $data['category_account_id'],
                amount: $data['amount']
            )
            ->credit(
                accountId: $data['payment_account_id'],
                amount: $data['amount']
            )
            ->post();
    }
}
