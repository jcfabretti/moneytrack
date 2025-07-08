<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    use HasFactory;

    protected $table = 'parceiros';
    protected $primaryKey = 'id'; // Adicionado explicitamente
    public $incrementing = true; // Adicionado explicitamente
    protected $keyType = 'int'; // Adicionado explicitamente

    protected $fillable = [
        'nome',
        'nat_jur',
        'tipo_cliente',
        'cod_fiscal',
        'localidade',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean', // O 'status' é tinyint(1), pode ser tratado como booleano
    ];

    public function LctoPartida()
    {
        return $this->hasMany(Lancamento::class, 'conta_partida', 'id');
    }

    public function LctoContraPartida()
    {
        return $this->hasMany(Lancamento::class, 'conta_contrapartida', 'id');
    }
}