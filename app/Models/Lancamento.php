<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
// use CreatedByUpdatedBy; // <--- REMOVER TEMPORARIAMENTE PARA DEPURAR

class Lancamento extends Model
{
    use HasFactory;
    protected $table = 'lancamentos';
    protected $primaryKey = 'id';
    public $incrementing = true;
    // CORREÇÃO CRÍTICA: Desabilitar os timestamps automáticos do Laravel
    // se created_by e updated_by são IDs de usuário, não timestamps de data/hora.
    public $timestamps = false; // <--- MUDAR PARA FALSE

    protected $keyType = 'int';
    protected $connection = 'mysql';

    // REMOVER: Se 'created_by' e 'updated_by' são colunas reais do banco de dados,
    // NÃO DEVEM ser 'appended' a menos que você tenha um accessor para eles.
    // protected $appends = ['created_by', 'updated_by']; // <--- REMOVER ESTA LINHA

    // REMOVER: Atributos padrão para colunas que já existem no banco de dados.
    // protected $attributes = [
    //     'created_by' => null,
    //     'updated_by' => null,
    // ]; // <--- REMOVER ESTA SEÇÃO

    protected $fillable = [
        'grupo_economico_id',
        'empresa_id',
        'data_lcto',
        'tipo_docto',
        'numero_docto',
        'tipo_conta',
        'conta_partida',
        'conta_contrapartida',
        'plano_contas_conta',
        'categorias_id',
        'historico',
        'unidade',
        'quantidade',
        'valor',
        'centro_custo',
        'vencimento',
        'origem',
        'created_by', // Já está em $fillable, ótimo!
        'updated_by'  // Já está em $fillable, ótimo!
    ];

    protected $casts = [
        'data_lcto' => 'date', // Certifique-se de que a coluna data_lcto seja tratada como data
        'vencimento' => 'date',
        'valor' => 'decimal:2',
        // CORREÇÃO: Remover casts para created_at e updated_at se essas colunas não existem
        // e você está usando 'created_by' e 'updated_by' como IDs.
        // 'created_at' => 'datetime', // <--- REMOVER ESTAS LINHAS SE created_at/updated_at NÃO EXISTEM OU SÃO IDs
        // 'updated_at' => 'datetime', // <--- REMOVER ESTAS LINHAS SE created_at/updated_at NÃO EXISTEM OU SÃO IDs
    ];

    public function LctoPartida(): BelongsTo
    {
        return $this->belongsTo(Parceiro::class,'conta_partida','id');
    }

    public function LctoContraPartida(): BelongsTo
    {
        return $this->belongsTo(Parceiro::class,'conta_contrapartida','id');
    }

    /*  VERIFICAR ONDE ESTÁ UTILIZANDO E ALTERAR PARA A QUE ESTÁ EM SEGUIDA ####################### */
    // REMOVER ESTE MÉTODO DUPLICADO (manter apenas o 'categoria()')
    // public function Categorias(): BelongsTo
    // {
    //     return $this->belongsTo(Categoria::class, 'categorias_id', 'id');
    // }

       /*  ACERTAR PARA UMA SÓ ################################################################ */
    public function categoria(): BelongsTo // <--- RENOMEAR PARA FUNÇÃO `categoria()` se o nome da função for em CamelCase.
    {
        return $this->belongsTo(Categoria::class, 'categorias_id', 'id');
    }

    public function categorias(): BelongsTo // <--- RENOMEAR PARA FUNÇÃO `categoria()` se o nome da função for em CamelCase.
    {
        return $this->belongsTo(Categoria::class, 'categorias_id', 'id');
    }
    /*  ACERTAR PARA UMA SÓ ################################################################ */

    // REMOVER: Esta propriedade não é válida no Eloquent Model.
    // protected $foreignKey = ['created_by', 'updated_by']; // <--- REMOVER ESTA LINHA

    public function usuarioQueCriou(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function usuarioQueAtualizou(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
