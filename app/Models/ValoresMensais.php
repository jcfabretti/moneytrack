<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ValoresMensais extends Model
{
    use HasFactory;

    protected $table = 'valores_mensais'; // Nome da tabela
    protected $fillable = [
        'empresa_id',
        'nome_empresa',
        'tipo_dado',
        'item_id',        // Certifique-se que está aqui
        'item_nome',      // Certifique-se que está aqui
        'quantidade_numerica',
        'valor_monetario',
        'mes_ano',
    ];

    /**
     * Define o relacionamento com a empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
