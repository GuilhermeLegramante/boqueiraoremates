<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type_id',
        'client_id',
        'path'
    ];

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    protected static function booted()
    {
        static::created(function ($document) {
            $document->loadMissing('documentType');

            if ($document->client_id && $document->documentType) {
                // CORREÇÃO: Verifica se existe um usuário logado ou usa o dono do documento
                // Se for um cadastro novo, auth()->id() pode falhar.
                $userId = auth()->id() ?: $document->user_id;

                // Se ainda assim não tiver user_id (caso de cadastro novo), 
                // precisamos garantir um ID válido ou pular a nota automática
                if ($userId) {
                    \App\Models\ClientNote::create([
                        'client_id' => $document->client_id,
                        'user_id'   => $userId,
                        'content'   => "Documento {$document->documentType->name} foi adicionado ao cliente.",
                    ]);
                }
            }
        });
    }
}
