<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Parceiro; // <-- ADICIONADO: Importação do modelo Parceiro
use App\Models\Categoria; // <-- ADICIONADO: Importação do modelo Categoria (se ainda não estiver lá)
use App\Models\User; // <-- ADICIONADO: Importação do modelo User (se ainda não estiver lá)
// use CreatedByUpdatedBy; // Se esta classe não está sendo usada ou está causando problemas, mantenha comentada.

class Lancamento extends Model
{
    use HasFactory;
    protected $table = 'lancamentos';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $connection = 'mysql';

    // MUITO IMPORTANTE: Se suas colunas 'created_by' e 'updated_by'
    // armazenam IDs de usuário e NÃO são timestamps automáticos do Laravel (created_at/updated_at),
    // então 'public $timestamps' DEVE ser 'false'.
    // Se você tem colunas 'created_at' e 'updated_at' no banco de dados e quer que o Laravel as gerencie,
    // então 'public $timestamps' DEVE ser 'true' e você NÃO precisaria de 'created_by'/'updated_by'
    // para timestamps.
    public $timestamps = true; // Mantenha como false se 'created_by' e 'updated_by' são IDs de usuário.

    // REMOVIDO: 'created_by' e 'updated_by' são colunas reais do banco de dados,
    // não atributos computados que precisam ser 'appended'.
    // protected $appends = ['created_by', 'updated_by'];

    // REMOVIDO: 'attributes' é para definir valores padrão para atributos que não existem.
    // 'created_by' e 'updated_by' são colunas do banco de dados.
    // protected $attributes = [
    //     'created_by' => null,
    //     'updated_by' => null,
    // ];

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
        'categorias_id', // Esta é a FK para a tabela 'categorias'
        'historico',
        'unidade',
        'quantidade',
        'valor',
        'centro_custo',
        'vencimento',
        'origem',
        'created_by', // Coluna para o ID do usuário que criou
        'updated_by'  // Coluna para o ID do usuário que atualizou
    ];

    protected $casts = [
        'data_lcto' => 'date',
        'vencimento' => 'date',
        'valor' => 'decimal:2',
        // REMOVIDO: Se 'public $timestamps = false;', então não há 'created_at'/'updated_at' para serem castados.
        // 'created_at' => 'datetime',
        // 'updated_at' => 'datetime',
    ];

    // Relação com Parceiro para a conta de partida
    public function LctoPartida(): BelongsTo
    {
        return $this->belongsTo(Parceiro::class, 'conta_partida', 'id');
    }

    // Relação com Parceiro para a conta de contrapartida
    public function LctoContraPartida(): BelongsTo
    {
        return $this->belongsTo(Parceiro::class, 'conta_contrapartida', 'id');
    }

    // CORREÇÃO CRÍTICA: Manter APENAS UMA relação para Categoria.
    // O nome do método deve ser consistente com o que você usa no controller e na Blade.
    // A convenção do Laravel para BelongsTo é usar o nome singular.
    public function categoria(): BelongsTo // Renomeado para 'categoria' (singular)
    {
        // 'categorias_id' é a chave estrangeira na tabela 'lancamentos'
        // 'numero_categoria' é a chave primária no modelo Categoria
        return $this->belongsTo(Categoria::class, 'categorias_id', 'numero_categoria');
    }

    // REMOVIDO: Método duplicado 'categorias()'. Mantenha apenas 'categoria()'.
    // public function categorias(): BelongsTo
    // {
    //     return $this->belongsTo(Categoria::class, 'categorias_id', 'numero_categoria');
    // }

    // Relação com Usuário para quem criou o lançamento
    public function usuarioQueCriou(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Relação com Usuário para quem atualizou o lançamento
    public function usuarioQueAtualizou(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
