<?php

namespace Sosupp\SlimerAccounting\Services;

use Illuminate\Support\Facades\DB;
use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class TrialBalanceService
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

    public function generateV2($fromDate = null, $toDate = null)
    {
        $query = DB::table('journal_entry_lines')
            ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_lines.journal_entry_id')
            ->join('accounts', 'accounts.id', '=', 'journal_entry_lines.account_id')
            ->select(
                'accounts.id',
                'accounts.code',
                'accounts.name',
                'accounts.type',
                DB::raw('SUM(journal_entry_lines.debit) as total_debit'),
                DB::raw('SUM(journal_entry_lines.credit) as total_credit')
            )
            ->groupBy('accounts.id', 'accounts.code', 'accounts.name', 'accounts.type');

        if ($fromDate) {
            $query->whereDate('journal_entries.date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('journal_entries.date', '<=', $toDate);
        }

        $results = $query->get();

        return $this->format($results);
    }

    protected function format($results)
    {
        $trialBalance = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($results as $row) {
            $balance = $row->total_debit - $row->total_credit;

            $trialBalance[] = [
                'account_id' => $row->id,
                'code' => $row->code,
                'name' => $row->name,
                'type' => $row->type,
                'debit' => $balance > 0 ? $balance : 0,
                'credit' => $balance < 0 ? abs($balance) : 0,
            ];

            $totalDebit += $balance > 0 ? $balance : 0;
            $totalCredit += $balance < 0 ? abs($balance) : 0;
        }

        return [
            'accounts' => $trialBalance,
            'totals' => [
                'debit' => $totalDebit,
                'credit' => $totalCredit,
                'balanced' => $totalDebit === $totalCredit,
            ]
        ];
    }

}
