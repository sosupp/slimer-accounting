<?php

namespace Sosupp\SlimerAccounting\Services;

use Illuminate\Support\Facades\DB;
use Sosupp\SlimerAccounting\Models\JournalEntry;
use Sosupp\SlimerAccounting\Models\JournalEntryLine;

class PostingService
{
    public function post(array $data)
    {
        return DB::transaction(function () use ($data) {

            $entry = JournalEntry::create([
                'journal_id' => $data['journal_id'],
                'transaction_date' => $data['transaction_date'],
                'reference' => $data['reference'],
                'description' => $data['description'],
                'status' => 'posted'
            ]);

            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($data['lines'] as $line) {

                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                    'description' => $line['description'] ?? null
                ]);

                $totalDebit += $line['debit'];
                $totalCredit += $line['credit'];
            }

            if ($totalDebit !== $totalCredit) {
                throw new \Exception('Journal entry is not balanced.');
            }

            return $entry;
        });
    }
}