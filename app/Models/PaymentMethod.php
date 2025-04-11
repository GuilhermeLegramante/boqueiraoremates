<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function getNameAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
