<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLogin extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address_id','login_status',
        'user_id','user_login_email',
        'user_login_pass_hash','referer_url',
        'user_session_id','user_agent',
        'quick_logout_reason',
        'created_at','updated_at'
    ];

    public function ip_address(){
        return $this->belongsTo(IpAddress::class);
    }
}
