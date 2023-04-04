<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Exam extends Model
{
    use HasFactory;
    use HasUlids;
    protected $fillable = ['exam', 'lesson_id','user_id', 'uni_code', 'description'];
    public function attachment(){
        return $this->hasOne(Exam_attachment::class, 'exam_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }
}
