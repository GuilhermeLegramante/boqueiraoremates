<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'finish_date', 'multiplier', 'note'];

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
        return $this->belongsToMany(Animal::class, 'animal_event');
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
