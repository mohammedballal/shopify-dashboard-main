<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTable extends Model
{
    use HasFactory;
    protected $fillable = ['product_name','cost_usd','cost_brl','mark_up'];
}
