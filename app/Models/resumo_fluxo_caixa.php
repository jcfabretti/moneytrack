<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TiposPlanoConta;

class resumo_fluxo_caixa extends Model
{
    use HasFactory;

    protected $table = 'resumo_fluxo_caixa'; // Nome da tabela

    protected $fillable = [
        'empresa_id',
        'tipo_plano_contas_id',
        'categoria_id',
        'categoria_nome',
        'categoria_nivel',
        'categoria_pai_id',
        'ano',
        'mes',
        'valor_total',
    ];

    // Opcional: Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function tipoPlanoContas()
    {
        return $this->belongsTo(TiposPlanoConta::class, 'tipo_plano_contas_id');
    }
}
