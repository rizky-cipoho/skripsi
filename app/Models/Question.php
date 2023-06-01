<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Question extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = ['exam_id','remove'];

    public function questionData(){
        return $this->hasMany(QuestionData::class, 'question_id');
    }
    public function choice(){
        return $this->hasMany(Choice::class, 'question_id');
    }

    public function point(){
        return $this->hasOne(Point::class, 'question_id');
    }
    
}
