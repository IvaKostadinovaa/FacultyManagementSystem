<?php


namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\EnrollmentStatus;
use App\Listeners\SendEnrollmentStatusNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\EnrollmentStatus::class => [
            \App\Listeners\SendEnrollmentStatusNotification::class,
        ],
    ];
}
