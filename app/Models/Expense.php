<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['name','notify_me','notification_date','amount','recurring_type','date','repeat_date','repeat_day','description','category_id'];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
