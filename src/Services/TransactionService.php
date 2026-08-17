<?php

namespace Sosupp\SlimerAccounting\Services;

use Sosupp\SlimerAccounting\Services\Transactions\Expense;
use Sosupp\SlimerAccounting\Services\Transactions\Income;

class TransactionService
{
    public function create(array $data)
    {
        return match ($data['type']) {
            'expense' =>
                app(Expense::class)
                    ->handle($data),

            'income' =>
                app(Income::class)
                    ->handle($data),

            default =>
                throw new \Exception(
                    'Unsupported transaction type'
                ),
        };
    }
}
