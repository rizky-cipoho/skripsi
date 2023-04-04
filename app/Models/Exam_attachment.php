<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_attachment extends Model
{
    use HasFactory;
    protected $fillable = ['filename', 'type','size', 'image', 'exam_id','path'];

}
