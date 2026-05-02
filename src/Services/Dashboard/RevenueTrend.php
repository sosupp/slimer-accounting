<?php

namespace Sosupp\SlimerAccounting\Services\Dashboard;

use Illuminate\Support\Facades\DB;
use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class RevenueTrend
{
    public function generate($from, $to)
    {
        return JournalEntryLine::query()
            ->whereHas('entry', function ($q) use ($from, $to) {
                $q->where('status', 'posted')
                  ->whereBetween('date', [$from, $to]);
            })
            ->whereHas('account', fn ($q) => $q->where('type', 'income'))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(credit - debit) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
