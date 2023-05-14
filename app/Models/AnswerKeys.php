<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class AnswerKeys extends Model
{
    use HasUlids;
    use HasFactory;
    protected $fillable = ['choice_id', 'remove', 'question_id'];

    public function choice(){
        return $this->belongsTo(Choice::class, 'choice_id');
    }
    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }
}
