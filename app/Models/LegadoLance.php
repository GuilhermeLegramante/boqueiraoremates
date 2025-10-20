<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegadoLance extends Model
{
    protected $table = 'legado_lances';
    protected $primaryKey = 'idlance';
    public $timestamps = false;

    protected $fillable = [
        'idanimais',
        'idcomprador',
        'datalance',
        'horalance',
        'idleilao',
        'lance',
        'situacao',
    ];

    // Relação com Cliente
    public function cliente()
    {
        return $this->belongsTo(LegadoCliente::class, 'idcomprador', 'idclientes');
    }

    // Relação com Animal
    public function animal()
    {
        return $this->belongsTo(LegadoDadosAnimal::class, 'idanimais', 'idanimais');
    }

    // Relação com Leilão
    public function leilao()
    {
        return $this->belongsTo(LegadoLeilao::class, 'idleilao', 'idleilao');
    }
}
