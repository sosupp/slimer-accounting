<?php

namespace Sosupp\SlimerAccounting\Models\Traits;

use Illuminate\Support\Str;

trait WithUid
{
    public static function bootWithUid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uid)) {
                $model->uid = (string) Str::orderedUuid();
            }
        });

        static::updating(function ($model) {
            if (empty($model->uid)) {
                $model->uid = (string) Str::orderedUuid();
            }
        });
    }
}
