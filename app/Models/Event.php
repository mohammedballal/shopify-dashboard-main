<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Label;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        "creator",
        "label",
        "title",
        "start_date",
        "end_date",
        "all_day",
        "url",
        "location",
        "description",
    ];
    public function user(){
        return $this->belongsTo(User::class,'creator');
    }

    public function event_label(){
        return $this->belongsTo(EventLabel::class,'label');
    }

    public function guests(){
        return $this->belongsToMany(EventGuest::class,'event_guests','event_id','user_id');
    }
}
