<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'breed_id',
        'animal_type_id',
        'coat_id',
        'gender',
        'register',
        'sbb',
        'rb',
        'mother',
        'father',
        'breeding',
        'blood_level',
        'blood_percentual',
        'quantity',
        'birth_date'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'blood_percentual' => 'double',
    ];

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function coat(): BelongsTo
    {
        return $this->belongsTo(Coat::class);
    }

    public function animalType(): BelongsTo
    {
        return $this->belongsTo(AnimalType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orderForEvent($eventId)
    {
        return $this->hasOne(Order::class)->where('event_id', $eventId);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'animal_event');
    }

    public function getNameAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getRegisterAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getSbbAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getRbAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getMotherAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getFatherAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getBreedingAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
