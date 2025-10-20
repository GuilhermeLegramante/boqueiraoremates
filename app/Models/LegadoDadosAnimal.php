<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegadoDadosAnimal extends Model
{
    use HasFactory;

    protected $table = 'legado_dadosanimais';
    protected $primaryKey = 'idanimais';
    public $timestamps = false;

    protected $fillable = [
        'idleilao', 'especie', 'nlote', 'nome', 'genero', 'situacaomacho', 'rp', 'dtdonascimento',
        'pelagem', 'pai', 'mae', 'linkqg', 'linkfotopq', 'linkfotogr', 'linkfotocarimbo', 'linkv1',
        'linkv2', 'comentarios', 'lancealvo', 'implemento', 'lanceatual', 'vlrinicial', 'lanceminimo',
        'lanceemanalise', 'situacaolance', 'idcompradoratual', 'ativo', 'vendido', 'linkregla',
        'linkfe1', 'linkfe2', 'linkfe3', 'linkfe4'
    ];
}
