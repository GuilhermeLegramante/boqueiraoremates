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
        'photo',
        'photo_full',
        'note',
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
        'birth_date',
        'video_link',
        'generation_link'
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
        return $this->belongsToMany(Event::class, 'animal_event')
            ->using(AnimalEvent::class)  // usa a model do pivot
            ->withPivot([
                'id',
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

    /**
     * Último lance aprovado ou não para este animal no evento atual.
     */
    public function getCurrentBidAttribute()
    {
        if (!isset($this->pivot)) {
            return 0;
        }

        // Pega o ID do pivot (animal_event_id)
        $animalEventId = $this->pivot->id;

        // Busca o maior lance para esse animal_event
        $bid = Bid::where('animal_event_id', $animalEventId)
            ->orderByDesc('amount')
            // ->where('status', 1)
            ->first();

        return $bid ? $bid->amount : 0;
    }

    /**
     * Próximo lance mínimo considerando o increment_value da pivot.
     */
    public function getNextBidAttribute()
    {
        if (!isset($this->pivot)) {
            return 0;
        }

        $increment = $this->pivot->increment_value ?? 0;

        if ($this->current_bid > 0) {
            return $this->current_bid + $increment;
        } else {
            return $this->pivot->min_value + $increment;
        }
    }
}
