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
        'banner_min',
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
        'regulation_image_path',
        'benefits_image_path',

    ];

    protected $casts = [
        'start_date' => 'datetime',
        'finish_date' => 'datetime',
        'multiplier' => 'double',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function animals()
    {
        return $this->belongsToMany(Animal::class, 'animal_event')
            ->using(AnimalEvent::class)
            ->withPivot([
                'name',
                'situation',
                'lot_number',
                'min_value',
                'increment_value',
                'target_value',
                'final_value',
                'status',
                'photo',
                'photo_full',
                'note',
                'video_link',
            ])
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
