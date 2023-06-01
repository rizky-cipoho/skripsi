<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class HistoryQuestion extends Model
{
    use HasUlids;
    use HasFactory;

    protected $fillable = [
        'history_id',
        'line',
        'lineBlock',
        'question_id',
    ];
    public function questionData(){
        return $this->hasMany(HistoryQuestionData::class, 'history_question_id');
    }
    public function choice(){
        return $this->hasMany(HistoryChoice::class, 'history_question_id');
    }
    public function answer(){
        return $this->hasOne(Answer::class, 'history_question_id');
    }
    public function pin(){
        return $this->hasOne(Pin::class, 'history_question_id');
    }
    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }
}
