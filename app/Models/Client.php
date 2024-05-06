<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'name',
        'email',
        'birth_date',
        'gender',
        'cpf_cnpj',
        'rg',
        'mother',
        'father',
        'whatsapp',
        'cel_phone',
        'business_phone',
        'home_phone',
        'bank_id',
        'bank_agency',
        'current_account',
        'address_id',
        'occupation',
        'note_occupation',
        'income',
        'establishment',
        'has_register_in_another_auctioneer',
        'auctioneer',
        'situation',
        'register_origin',
        'profile',
        'note',
    ];

    protected $casts = [
        'documents' => 'array',
        'has_register_in_another_auctioneer' => 'boolean',
        // 'income' => 'double',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function documentTypes(): HasManyThrough
    {
        return $this->hasManyThrough(DocumentType::class, Document::class);
    }
}
