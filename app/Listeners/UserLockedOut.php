<?php

namespace App\Listeners;

use App\Models\IpAddress;
use App\Models\User;
use App\Notifications\LockedOut;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UserLockedOut
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Lockout $event)
    {
        Log::info("============================================================");
        Log::alert("User Lockout with email : ('".$event->request->email."')");
        Log::alert("User Referrer : ('".$event->request->header('Referer')."')");
        Log::info("============================================================");
        $request_ip = request()->ip();
        $ip = IpAddress::where('ip_address',$request_ip)->first();
        if ($ip){
            $ip->un_successful_logins = ($ip->un_successful_logins ?? 0) + 1;
            // Block IP if it's already not blocked
            if ($ip->status)
                $ip->status  = "0";
            $ip->save();
        }
        else{
            IpAddress::create(
                ['type'=>'auto','status'=>'0','ip_address'=>$request_ip,
                 'successful_logins'=>0,'un_successful_logins'=>1,'created_at'=>now(),'updated_at'=>now()]);
        }
//        if ($user = User::where('email', $event->request->email)->first()) {
//            $user->notify(new LockedOut);
//        }
    }
}
