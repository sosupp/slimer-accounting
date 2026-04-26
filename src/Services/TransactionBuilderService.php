<?php

namespace Sosupp\SlimerAccounting\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Sosupp\SlimerAccounting\Models\JournalEntry;

class TransactionBuilderService
{
    protected $lines = [];
    protected $data = [];

    public function create(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function debit($accountId, $amount, $description = null)
    {
        $this->lines[] = [
            'account_id' => $accountId,
            'debit' => $amount,
            'credit' => 0,
            'description' => $description
        ];

        return $this;
    }

    public function credit($accountId, $amount, $description = null)
    {
        $this->lines[] = [
            'account_id' => $accountId,
            'debit' => 0,
            'credit' => $amount,
            'description' => $description
        ];

        return $this;
    }

    public function validate()
    {
        $debit = collect($this->lines)->sum('debit');
        $credit = collect($this->lines)->sum('credit');

        if ($debit !== $credit) {
            throw new \Exception('Journal entry not balanced');
        }

        if (count($this->lines) < 2) {
            throw new \Exception('Minimum two lines required');
        }
    }

    public function post()
    {
        $this->validate();

        return DB::transaction(function () {

            $transaction = JournalEntry::create([
                ...$this->data,
                'status' => 'posted',
                'posted_at' => now(),
            ]);

            foreach ($this->lines as $line) {
                $transaction->lines()->create($line);
            }

            return $transaction;
        });
    }


    public function reverse(JournalEntry $transaction)
    {
        if ($transaction->status !== 'posted') {
            throw new Exception('Only posted entries can be reversed');
        }

        $this->create([
            'date' => now(),
            'description' => 'Reversal of ' . $transaction->id,
            'reversed_transaction_id' => $transaction->id
        ]);

        foreach ($transaction->lines as $line) {
            $this->debit($line->account_id, $line->credit);
            $this->credit($line->account_id, $line->debit);
        }

        return $this->post();
    }
}