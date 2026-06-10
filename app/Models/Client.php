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
use Illuminate\Support\Facades\Hash;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'is_international',
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
        'income_range',
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
        static::creating(function ($client) {
            if ($client->is_international) {
                return static::handleInternationalClient($client);
            }

            return static::handleNationalClient($client);
        });


        static::updated(function ($client) {
            // 🔹 Atualiza o nome do usuário vinculado
            if ($client->registeredUser) {
                $client->registeredUser->update([
                    'name' => $client->name,
                ]);
            }

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

        // Excluindo cliente
        static::deleting(function ($client) {
            // Excluir documentos relacionados
            $client->documents()->delete();

            // Excluir usuário vinculado
            if ($client->registeredUser) {
                $client->registeredUser->delete();
            }
        });
    }

    /**
     * Orquestra a criação de clientes internacionais e seus respectivos usuários.
     */
    private static function handleInternationalClient($client): void
    {
        $username = $client->email;

        // Gera senha temporária baseada no Whatsapp ou fallback seguro
        $rawWhatsapp = preg_replace('/\D/', '', $client->whatsapp ?? '');
        $password = substr($rawWhatsapp, 0, 6);
        if (empty($password)) {
            $password = '123456';
        }

        // Busca usuário existente pelo e-mail
        $existingUser = User::where('email', $client->email)->first();

        if ($existingUser) {
            $client->registeredUser()->associate($existingUser);
        } else {
            $user = User::create([
                'name'     => $client->name,
                'username' => $username,
                'email'    => $client->email,
                'password' => Hash::make($password),
            ]);

            $user->assignRole('client');
            $client->registeredUser()->associate($user);
        }
    }

    /**
     * Orquestra a validação e criação de clientes nacionais (com CPF/CNPJ).
     * Retorna 'false' se a criação do registro precisar ser abortada.
     */
    private static function handleNationalClient($client): bool
    {
        $cpfCnpj = preg_replace('/\D/', '', $client->cpf_cnpj);
        $password = substr($cpfCnpj, 0, 6);
        $email = $client->email ?? "{$cpfCnpj}@example.com";

        // Evita duplicidade de cliente nacional
        $existingClient = Client::where('cpf_cnpj', $cpfCnpj)
            ->orWhere('cpf_cnpj', $client->cpf_cnpj)
            ->first();

        if ($existingClient) {
            $existingClient->update([
                'name' => $client->name,
                'email' => $client->email,
            ]);

            return false; // Aborta a criação do novo registro no banco
        }

        // Busca ou vincula o usuário correspondente ao CPF/CNPJ
        $existingUser = User::where('username', $cpfCnpj)
            ->orWhere('username', $client->cpf_cnpj)
            ->first();

        if ($existingUser) {
            $client->registeredUser()->associate($existingUser);
        } else {
            $user = User::create([
                'name'     => $client->name,
                'username' => $client->cpf_cnpj,
                'email'    => $email,
                'password' => Hash::make($password),
            ]);

            $user->assignRole('client');
            $client->registeredUser()->associate($user);
        }

        return true;
    }
}
