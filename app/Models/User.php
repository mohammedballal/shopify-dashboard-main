<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'status',
        'tag_id',
        'avatar',
        'email',
        'password',
        'commission',
        'google2fa_secret'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shops(){
        return $this->belongsToMany(Shop::class,'shop_users');
    }

    public function orders(){
        return $this->hasMany(Order::class,'user_id');
    }

    public function events(){
        //all events as a creator
        return $this->hasMany(Event::class,'creator');
    }

    public function eventsAsGuest(){
        return $this->hasMany(EventGuest::class);
    }

    public function setGoogle2faSecretAttribute($value)
    {
        if (!empty($value))
            $this->attributes['google2fa_secret'] = encrypt($value) ?? NULL;
        else{
            $this->attributes['google2fa_secret'] = NULL;
        }
    }

    public function getGoogle2faSecretAttribute($value)
    {
        if (!empty($value))
            return decrypt($value);
    }

    public function sales(): float
    {
        if ($this->hasRole('Super Admin')){
            $sales  = Order::sum('total_usd');
        }else{
            $coupons = $this->tag_id?json_decode($this->tag_id):array();
            $sales= 0;
            foreach ($coupons as $coupon) {
                $sales  += Order::where('tags', 'LIKE', "%$coupon%")->sum('total_usd');
            }
        }
        return $sales;
    }
    public function products(): int
    {
        if ($this->hasRole('Super Admin')){
            $products  = Order::sum('items_count');
        }else{

            $coupons = $this->tag_id?json_decode($this->tag_id):array();
            $products= 0;
            foreach ($coupons as $coupon) {
                $products  += Order::where('tags','LIKE',"%$coupon%")->sum('items_count');
            }
        }
        return $products;
    }
}
