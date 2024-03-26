<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'finish_date', 'multiplier', 'note'];

    protected $casts = [
        'start_date' => 'date',
        'finish_date' => 'date',
        'multiplier' => 'double',
    ];
}
