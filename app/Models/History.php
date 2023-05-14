<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasUlids;
    use HasFactory;    
    protected $fillable = [
        'exam_id',
        'user_id',
    ];
    public function exam(){
        return $this->belongsTo(Exam::class);
    }
    public function lesson(){
        return $this->belongsTo(lesson::class);
    }
    public function question(){
        return $this->hasMany(HistoryQuestion::class, 'history_id');
    }
}
