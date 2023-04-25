<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Question_attachment extends Model
{
    use HasUlids;
    use HasFactory;
    protected $fillable = ['filename','path','type','size', 'caption', 'withBorder', 'stretched', 'withBackground'];

}
