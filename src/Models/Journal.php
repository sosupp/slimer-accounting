<?php

namespace Sosupp\SlimerAccounting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sosupp\SlimerAccounting\Models\Traits\WithUid;

class Journal extends Model
{
    use HasFactory, SoftDeletes, WithUid;

    protected $fillable = [
        'uid', 'name', 'code', 'type', 'description',
        'slug',
    ];

    protected $casts = [
        'type' => 'array',
    ];
    
    // relationships
    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }


    
}