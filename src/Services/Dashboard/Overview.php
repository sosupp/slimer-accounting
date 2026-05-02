<?php

namespace Sosupp\SlimerAccounting\Services\Dashboard;

use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class Overview
{
    public function generate($from = null, $to = null)
    {
        $lines = JournalEntryLine::query()
            ->whereHas('entry', function ($q) use ($from, $to) {
                $q->where('status', 'posted');

                if ($from) $q->whereDate('date', '>=', $from);
                if ($to) $q->whereDate('date', '<=', $to);
            })
            ->with('account')
            ->get();

        $income = 0;
        $expenses = 0;
        $cash = 0;

        foreach ($lines as $line) {
            $type = $line->account->type;

            if ($type === 'income') {
                $income += ($line->credit - $line->debit);
            }

            if ($type === 'expense') {
                $expenses += ($line->debit - $line->credit);
            }

            if ($line->account->code === '1110') { // Cash on Hand
                $cash += ($line->debit - $line->credit);
            }
        }

        return [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $income - $expenses,
            'cash' => $cash,
        ];
    }
}
