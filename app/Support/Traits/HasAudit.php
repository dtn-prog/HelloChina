<?php

namespace App\Support\Traits;

use App\Core\Audit\Services\AuditService;

trait HasAudit
{
    public static function bootHasAudit(): void
    {
        static::created(function ($model) {
            app(AuditService::class)->logCreate($model, $model->toArray());
        });

        static::updated(function ($model) {
            $old = $model->getOriginal();
            $new = $model->getAttributes();
            app(AuditService::class)->logUpdate($model, $old, $new);
        });

        static::deleted(function ($model) {
            app(AuditService::class)->logDelete($model);
        });
    }
}
