<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'created',
                'model_type' => class_basename($model),
                'model_id'   => $model->id,
                'new_values' => $model->toArray(),
                'ip_address' => request()->ip(),
            ]);
        });

        static::updated(function ($model) {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'updated',
                'model_type' => class_basename($model),
                'model_id'   => $model->id,
                'old_values' => $model->getRawOriginal(),
                'new_values' => $model->getChanges(),
                'ip_address' => request()->ip(),
            ]);
        });

        static::deleted(function ($model) {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'deleted',
                'model_type' => class_basename($model),
                'model_id'   => $model->id,
                'old_values' => $model->toArray(),
                'ip_address' => request()->ip(),
            ]);
        });
    }
}
