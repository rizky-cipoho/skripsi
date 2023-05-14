<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class TimeStart extends Model
{
        use HasUlids;
    use HasFactory;
    protected $fillable = [
        'start',
        'over',
    ];
}
