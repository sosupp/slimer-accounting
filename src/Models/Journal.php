<?php

namespace Sosupp\SlimerAccounting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uid', 'name', 'code', 'type', 'description'
    ];

    // relationships
    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }


    
}