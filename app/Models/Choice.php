<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasUlids;
    use HasFactory;
    protected $fillable = ['question_id','choice', 'remove', 'choice_attachment'];

    public function attachment(){
        return $this->belongsTo(Choice_attachment::class, 'choice_attachment');
    }
    public function keys(){
        return $this->hasOne(AnswerKeys::class, 'choice_id');
    }
}
