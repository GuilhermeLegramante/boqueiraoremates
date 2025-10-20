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
            // Garante que o tipo de documento está carregado
            $document->loadMissing('documentType');

            if ($document->client_id && $document->documentType) {
                ClientNote::create([
                    'client_id' => $document->client_id,
                    'user_id' => Auth::id(),
                    'content' => "Documento {$document->documentType->name} foi adicionado ao cliente.",
                ]);
            }
        });
    }
}
