<?php
namespace Sosupp\SlimerAccounting\Services;

use Illuminate\Support\Collection;
use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class LedgerService
{
    public function getAccountLedger(
        int $accountId,
        ?string $fromDate = null,
        ?string $toDate = null
    ) {
        $openingBalance = $this->calculateOpeningBalance(
            $accountId,
            $fromDate
        );

        $query = JournalEntryLine::query()
            ->with([
                'entry.journal',
                'account'
            ])
            ->where('account_id', $accountId)
            ->whereHas('entry', function ($q) use (
                $fromDate,
                $toDate
            ) {
                $q->where('status', 'posted');

                if ($fromDate) {
                    $q->whereDate(
                        'date',
                        '>=',
                        $fromDate
                    );
                }

                if ($toDate) {
                    $q->whereDate(
                        'date',
                        '<=',
                        $toDate
                    );
                }
            })
            ->orderBy('created_at');

        $lines = $query->get();

        return $this->formatLedger(
            $lines,
            $openingBalance
        );
    }

    protected function calculateOpeningBalance(
        int $accountId,
        ?string $fromDate
    ) {
        if (!$fromDate) {
            return 0;
        }

        $lines = JournalEntryLine::query()
            ->where('account_id', $accountId)
            ->whereHas('entry', function ($q) use ($fromDate) {
                $q->where('status', 'posted')
                  ->whereDate(
                      'date',
                      '<',
                      $fromDate
                  );
            })
            ->get();

        $openingBalance = 0;

        foreach ($lines as $line) {
            $openingBalance += $line->debit;
            $openingBalance -= $line->credit;
        }

        return $openingBalance;
    }

    protected function formatLedger(
        array|Collection $lines,
        float|int $openingBalance
    ) {
        $runningBalance = $openingBalance;

        $records = [];

        // opening row
        $records[] = [
            'date' => null,
            'reference' => null,
            'journal' => null,
            'description' => 'Opening Balance',
            'debit' => null,
            'credit' => null,
            'balance' => $openingBalance
        ];

        foreach ($lines as $line) {

            $runningBalance += $line->debit;
            $runningBalance -= $line->credit;

            $records[] = [
                'date' => $line->entry->date,
                'reference' => $line->entry->reference,
                'journal' => $line->entry->journal->name,
                'description' => $line->description
                    ?? $line->entry->description,
                'debit' => $line->debit,
                'credit' => $line->credit,
                'balance' => $runningBalance
            ];
        }

        return [
            'account' => $lines->first()?->account?->name,
            'opening_balance' => $openingBalance,
            'records' => $records,
            'closing_balance' => $runningBalance
        ];
    }
}