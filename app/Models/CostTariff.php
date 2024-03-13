<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostTariff extends Model
{
    use HasFactory;

    protected $fillable = ['name','frequency','value','total'];
}
