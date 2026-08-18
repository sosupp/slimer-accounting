<?php

namespace Sosupp\SlimerAccounting\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sosupp\SlimerAccounting\Models\Traits\WithUid;

class JournalEntryLine extends Model
{
    use HasFactory, SoftDeletes, WithUid;

    protected $guarded = [];

    public function entry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // scopes
    public function scopeOfType(Builder $query, array $types): Builder
    {
        return $query->whereHas('account', function ($q) use ($types) {
            $q->whereIn('type', $types);
        });
    }

    // Filter lines by branch (assumes branch_id lives on your journal_entries header table)
    public function scopeForBranch(Builder $query, $branchId): Builder
    {
        if (!$branchId) return $query;
        
        return $query->whereHas('journalEntry', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId); // Ensure branch_id is added to your journal_entries table
        });
    }
}
