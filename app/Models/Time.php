<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasUlids;
    use HasFactory;
    protected $fillable = [
        'duration',
        'start',
        'remove',
        'time_starts_id',
    ];
    public function startTime(){
        return $this->belongsTo(TimeStart::class, 'time_starts_id');
    }
}
