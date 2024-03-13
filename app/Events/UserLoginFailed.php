<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoginFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $quick_logout_reason,$user_id,
    $ip,$login_email,$login_pass,$referer,$session_id,$user_agent;
    public function __construct($ip,$login_email,$login_pass,$referer,$session_id,$user_agent,$user_id = null,$quick_logout_reason = null)
    {
        $this->ip = $ip;
        $this->login_email = $login_email;
        $this->login_pass = $login_pass;
        $this->referer = $referer;
        $this->session_id = $session_id;
        $this->user_agent = $user_agent;

        $this->quick_logout_reason = $quick_logout_reason;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
