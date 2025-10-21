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
use Illuminate\Support\Facades\Auth;

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
        'inscricaoestadual',
        'instagram',
        'facebook',
    ];

    protected $casts = [
        'documents' => 'array',
        'has_register_in_another_auctioneer' => 'boolean',
        'income' => 'double',
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

    public function sellerOrders()
    {
        return $this->hasMany(\App\Models\Order::class, 'seller_id', 'id');
    }

    // Campos de texto com exibição em maiúsculas
    public function getNameAttribute($value)
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

    public function getOccupationAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getNoteOccupationAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getEstablishmentAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getAuctioneerAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    // public function getRegisterOriginAttribute($value)
    // {
    //     return mb_strtoupper($value, 'UTF-8');
    // }

    // public function getProfileAttribute($value)
    // {
    //     return mb_strtoupper($value, 'UTF-8');
    // }

    public function getNoteAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    // Usuário vinculado ao cliente (via cadastro no site)
    public function registeredUser()
    {
        return $this->belongsTo(User::class, 'registered_user_id');
    }

    public function notes()
    {
        return $this->hasMany(ClientNote::class);
    }

    protected static function booted()
    {
        static::updated(function ($client) {
            // Verifica quais atributos foram alterados
            $changes = $client->getDirty();

            // Remove campos irrelevantes (ex: updated_at)
            unset($changes['updated_at']);

            // Só cria anotação se houve mudanças reais
            if (count($changes) > 0) {
                $changedFields = collect($changes)
                    ->map(function ($newValue, $field) use ($client) {
                        $oldValue = $client->getOriginal($field);

                        // formata diferença
                        return "**{$field}**: '{$oldValue}' → '{$newValue}'";
                    })
                    ->implode(", ");

                ClientNote::create([
                    'client_id' => $client->id,
                    'user_id' =>  auth()->id() ?? 1,
                    'content' => "Cliente atualizado por " . (Auth::user()?->name ?? 'Sistema') . ". Campos alterados: {$changedFields}.",
                ]);
            }
        });
    }
}
