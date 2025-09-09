<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'banner',
        'start_date',
        'finish_date',
        'multiplier',
        'note',
        'regulation',
        'pre_start_date',
        'pre_finish_date',
        'published',
        'closed',
        'show_lots',
    ];

    protected $casts = [
        'start_date' => 'date',
        'finish_date' => 'date',
        'multiplier' => 'double',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function animals()
    {
        return $this->belongsToMany(Animal::class, 'animal_event')
            ->withPivot(['min_value', 'final_value', 'lot_number', 'increment_value', 'target_value', 'status'])
            ->withTimestamps();
    }

    public function getNameAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getNoteAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
