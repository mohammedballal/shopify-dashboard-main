<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    use HasFactory;
    protected $fillable = ['type','status','ip_address','successful_logins','un_successful_logins','created_at','updated_at'];

    public function scopeBlocked($query){
        return $query->where('status','0')->pluck('ip_address')->toArray();
    }

    public function system_logins(){
        return $this->hasMany(SystemLogin::class);
    }
}
