<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
