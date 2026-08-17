<?php

namespace Sosupp\SlimerAccounting\Services;

use Sosupp\SlimerAccounting\Services\PostingService;


class ExpenseTransaction
{
    public function __construct(
        protected PostingService $postingService
    ) {}

    public function handle(array $data)
    {
        return $this->postingService->post([

            'journal_id' => $data['journal_id'],

            'transaction_date' => $data['date'],

            'reference' => $data['reference'],

            'description' => $data['description'],

            'lines' => [

                [
                    'account_id' => $data['category_account_id'],

                    'debit' => $data['amount'],

                    'credit' => 0,
                ],

                [
                    'account_id' => $data['settlement_account_id'],

                    'debit' => 0,

                    'credit' => $data['amount'],
                ],

            ]

        ]);
    }
}
