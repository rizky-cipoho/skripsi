<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasUlids;
    use HasFactory;
    protected $fillable = ['question_id', 'exam_id', 'user_id', 'session_id', 'truth'];

    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }
}
