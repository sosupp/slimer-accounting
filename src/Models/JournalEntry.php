<?php

namespace Sosupp\SlimerAccounting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sosupp\SlimerAccounting\Models\Traits\WithUid;

class JournalEntry extends Model
{
    use HasFactory, SoftDeletes, WithUid;
    
    protected $guarded = [];

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function journalable()
    {
        return $this->morphTo();
    }

    public function reversal()
    {
        return $this->belongsTo(self::class, 'reversed_entry_id');
    }

    public function isBalanced()
    {
        return $this->lines->sum('debit') === $this->lines->sum('credit');
    }
}