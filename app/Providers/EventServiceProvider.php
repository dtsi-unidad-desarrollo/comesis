<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \Illuminate\Queue\Events\JobProcessing::class => [
            \App\Listeners\LogJobProcessing::class,
        ],
        \Illuminate\Queue\Events\JobProcessed::class => [
            \App\Listeners\LogJobProcessed::class,
        ],
        \Illuminate\Queue\Events\JobFailed::class => [
            \App\Listeners\LogJobFailed::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
