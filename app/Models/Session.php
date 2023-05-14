<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasUlids;
    use HasFactory;
    protected $fillable = [
        'token',
        'endToken',
        'user_id',
        'history_id',
        'exam_id',
        'over',
    ];
    public function exam(){
        return $this->belongsTo(Exam::class, 'exam_id');
    }
    public function history(){
        return $this->belongsTo(History::class, 'history_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
