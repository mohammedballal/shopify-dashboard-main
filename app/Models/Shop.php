<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'api_key',
        'api_pass',
        'api_version'
    ];
    protected $hidden = [
        'api_key',
        'api_pass',
    ];

    public function users(){
        return $this->belongsToMany(User::class,'shop_users');
    }

    public function orders(){
        return $this->hasMany(Order::class,'shop_id');
    }
}
