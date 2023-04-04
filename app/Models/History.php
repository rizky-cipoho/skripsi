<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    public function exam(){
        return $this->belongsTo(Exam::class);
    }
    public function lesson(){
        return $this->belongsTo(lesson::class);
    }
}
