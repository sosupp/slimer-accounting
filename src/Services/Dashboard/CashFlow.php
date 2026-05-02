<?php

namespace Sosupp\SlimerAccounting\Services\Dashboard;

use Illuminate\Support\Facades\DB;
use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class CashFlow
{
    public function generate(string $from, string $to)
    {
        return JournalEntryLine::query()
            ->whereHas('entry', function ($q) use ($from, $to) {
                $q->where('status', 'posted')
                  ->whereBetween('date', [$from, $to]);
            })
            ->whereHas('account', fn ($q) => $q->where('code', '1110')) // cash
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(debit - credit) as net_cash')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
