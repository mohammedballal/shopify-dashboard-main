<?php

namespace App\Listeners;

use App\Events\UserLoginSuccessful;
use App\Models\SystemLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserSuccessfulLogin
{

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(UserLoginSuccessful $event)
    {
        SystemLogin::create([
            'ip_address_id'=>$event->ip,
            'login_status'=>"1",
            'user_id'=>$event->user_id,
            'user_login_email'=>$event->login_email,
            'user_login_pass_hash'=>bcrypt($event->login_pass),
            'referer_url'=>$event->referer,
            'user_session_id'=>$event->session_id,
            'user_agent'=>$event->user_agent,
            'quick_logout_reason'=>$event->quick_logout_reason,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
    }
}
