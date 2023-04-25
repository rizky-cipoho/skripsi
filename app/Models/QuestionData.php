<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class QuestionData extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = ['data', 'type', 'question_id','remove', 'question_id', 'question_attachment'];
        public function questionAttachment(){
        return $this->belongsTo(Question_attachment::class, 'question_attachment');
    }
}
