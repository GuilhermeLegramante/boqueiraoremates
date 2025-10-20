<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegadoLeilao extends Model
{
    use HasFactory;

    protected $table = 'legado_leiloes';
    protected $primaryKey = 'idleilao';
    public $timestamps = false;

    protected $fillable = [
        'nomeleilao', 'apresentacao', 'descricao', 'dataleilao', 'horaleilao', 'datainicial',
        'datafinal', 'publicado', 'horainicial', 'horafinal', 'condicoes', 'linklogo',
        'linkregremate', 'linkregprova', 'tipoleilao', 'encerrado', 'visivel'
    ];
}
