<?php

namespace Sosupp\SlimerAccounting\Services;

use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class BalanceSheetService
{
    public function generate($asAt = null)
    {
        $query = JournalEntryLine::query()->with('account');

        if ($asAt) {
            $query->whereHas(
                'entry', 
                fn ($q) => $q->whereDate('date', '<=', $asAt)
            );
        }

        $lines = $query->get();

        $assets = 0;
        $liabilities = 0;
        $equity = 0;

        foreach ($lines as $line) {
            $type = $line->account->type;

            $balance = $line->debit - $line->credit;

            match ($type) {
                'asset' => $assets += $balance,
                'liability' => $liabilities += ($line->credit - $line->debit),
                'equity' => $equity += ($line->credit - $line->debit),
            };
        }

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'balanced' => $assets === ($liabilities + $equity),
        ];
    }

    protected function calculateRetainedEarnings($trialAccounts)
    {
        $income = 0;
        $expenses = 0;

        foreach ($trialAccounts as $account) {
            if ($account['type'] === 'income') {
                $income += $account['credit'];
            }

            if ($account['type'] === 'expense') {
                $expenses += $account['debit'];
            }
        }

        return $income - $expenses;
    }
}