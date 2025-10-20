<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegadoCliente extends Model
{
    use HasFactory;

    protected $table = 'legado_clientes';
    protected $primaryKey = 'idclientes';
    public $timestamps = false;

    protected $fillable = [
        'usuario', 'senha', 'nome', 'formachamado', 'email', 'cpf', 'cnpj', 'rg', 'mae', 'pai',
        'datanasc', 'cidade', 'estado', 'cep', 'logradouro', 'complemento', 'bairro', 'pais',
        'tcomercial', 'tresidencial', 'tcelular', 'tcelular2', 'banco', 'agencia', 'conta',
        'ativo', 'tipo', 'profissao', 'renda', 'datacadastro', 'observacao'
    ];
}
