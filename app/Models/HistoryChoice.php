<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class HistoryChoice extends Model
{
    use HasUlids;
    use HasFactory;
    protected $fillable = [
        'choice_id',
        'question_id',
        'history_question_id',
    ];
    public function choice(){
        return $this->belongsTo(Choice::class, 'choice_id');
    }
}
