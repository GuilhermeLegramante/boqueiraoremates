<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function getNameAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getCodeAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

}
