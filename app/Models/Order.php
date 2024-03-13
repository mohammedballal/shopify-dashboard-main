<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "order_no",
        "order_date",
        "customer_first_name",
        "customer_last_name",
        "customer_name",
        "shop_id",
        "store_currency",
        "total",
        "total_usd",
        "payment_status",
        "fulfillment_status",
        "items_count",
        "items_array",
        "delivery_method",
        "tags",
        "order_api_response"
    ];
    protected $casts = [
        'order_date' => 'datetime',
    ];
    // all columns
    protected $columns = [
        "id",
        "order_no",
        "order_date",
        "customer_first_name",
        "customer_last_name",
        "customer_name",
        "shop_id",
        "store_currency",
        "total",
        "total_usd",
        "payment_status",
        "fulfillment_status",
        "items_count",
        "items_array",
        "delivery_method",
        "tags",
        "order_api_response",
        "created_at",
        "updated_at"
    ];

    public function scopeExclude($query, $value = [])
    {
        return $query->select(array_diff($this->columns, (array) $value));
    }
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
