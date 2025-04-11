<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'postal_code',
        'street',
        'number',
        'complement',
        'reference',
        'district',
        'city',
        'state',
    ];

    public function getStreetAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getNumberAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getComplementAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getReferenceAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getDistrictAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getCityAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getStateAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
