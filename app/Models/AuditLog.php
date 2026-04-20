<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($log) {
            if (!$log->ip_address && request()) {
                $log->ip_address = request()->ip();
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

