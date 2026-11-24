<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'order'];

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $query) {
            $query->orderByRaw('CASE WHEN order = 0 THEN 1 ELSE 0 END')
                ->orderBy('order')
                ->orderBy('id');
        });
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function getNameAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
