<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasUlids;
    use HasFactory;
    protected $fillable = ['choice_id', 'history_question_id', 'user_id'];
    
}
