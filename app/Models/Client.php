<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    // protected $dates = ['birthDate'];

    protected $fillable = ['name'];

    // protected $map = [
    //     'birth_date' => 'birthDate',
    // ];

    // protected function getNomeDoAtributoAttribute()
    // {
    //     return ucfirst($this->attributes['name']);
    // }
}
