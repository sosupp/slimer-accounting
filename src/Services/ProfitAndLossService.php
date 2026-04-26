<?php

namespace Sosupp\SlimerAccounting\Services;

use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class ProfitAndLossService
{
    public function generate($from = null, $to = null)
    {
        $query = JournalEntryLine::query()
        ->with('account');

        if ($from) {
            $query->whereHas(
                'entry', 
                fn ($q) => $q->whereDate('date', '>=', $from)
            );
        }

        if ($to) {
            $query->whereHas(
                'entry', 
                fn ($q) => $q->whereDate('date', '<=', $to)
            );
        }

        $lines = $query->get();

        $income = 0;
        $expenses = 0;

        foreach ($lines as $line) {
            $type = $line->account->type;

            $amount = $line->credit - $line->debit;

            if ($type === 'income') {
                $income += $amount;
            }

            if ($type === 'expense') {
                $expenses += ($line->debit - $line->credit);
            }
        }

        return [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $income - $expenses,
        ];
    }
}