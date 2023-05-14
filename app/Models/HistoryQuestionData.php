<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class HistoryQuestionData extends Model
{
        use HasUlids;
    use HasFactory;
    protected $fillable = [
        'questionData_id',
        'question_id',
        'history_question_id'
    ];
    public function questionData(){
        return $this->belongsTo(QuestionData::class, 'questionData_id');
    }
}
