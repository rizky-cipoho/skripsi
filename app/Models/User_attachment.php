<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class User_attachment extends Model
{
    use HasFactory;
    use HasUlids;
    protected $fillable = ['filename','path','type','size', 'user_id'];
}
