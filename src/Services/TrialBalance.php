<?php

namespace Sosupp\SlimerAccounting\Services;

use Illuminate\Support\Facades\DB;
use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class TrialBalance
{
    public function generate($from = null, $to = null)
    {
        $query = JournalEntryLine::query()
            ->select(
                'account_id',
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
            ->groupBy('account_id');

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

        $lines = $query->with('account')->get();

        return $lines->map(function ($line) {
            return [
                'account' => $line->account->name,
                'debit' => $line->total_debit,
                'credit' => $line->total_credit,
                'balance' => $line->total_debit - $line->total_credit,
            ];
        });
    }
}