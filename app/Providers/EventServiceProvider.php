<?php

namespace App\Providers;

use App\Events\UserLoginFailed;
use App\Events\UserLoginSuccessful;
use App\Listeners\UserFailedLogin;
use App\Listeners\UserLockedOut;
use App\Listeners\UserSuccessfulLogin;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Lockout::class => [
            UserLockedOut::class,
        ],
        UserLoginSuccessful::class=>[
          UserSuccessfulLogin::class
        ],
        UserLoginFailed::class=>[
            UserFailedLogin::class
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
