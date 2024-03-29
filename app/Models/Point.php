<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasUlids;
    use HasFactory;
         protected $fillable = [
        'point',
        'question_id'
    ];
}
