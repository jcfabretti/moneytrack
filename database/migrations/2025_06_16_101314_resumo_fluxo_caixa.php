<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
   {
        Schema::create('resumo_fluxo_caixa', function (Blueprint $table) {
            $table->id();

            // IDs de contexto
            $table->unsignedBigInteger('empresa_id');
            // AQUI está o nome correto da coluna
            $table->unsignedBigInteger('tipos_plano_contas_id'); // <-- Nome da coluna está correto aqui

            // Detalhes da categoria
            $table->unsignedBigInteger('categoria_id');
            $table->string('categoria_nome');
            $table->unsignedTinyInteger('categoria_nivel');
            $table->unsignedBigInteger('categoria_pai_id')->nullable();

            // Período e Valor
            $table->unsignedSmallInteger('ano');
            $table->unsignedTinyInteger('mes');
            $table->decimal('valor_total', 15, 2);

            $table->timestamps();

            // Chaves Estrangeiras
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            // Nome da FK também está correto aqui
            $table->foreign('tipos_plano_contas_id')->references('id')->on('colecao_categorias')->onDelete('cascade');
            $table->foreign('categoria_id')->references('numero_categoria')->on('categorias')->onDelete('cascade');

            // --- CORREÇÃO AQUI NO ÍNDICE ---
            // O nome da coluna no índice deve ser 'tipos_plano_contas_id'
            $table->index(['empresa_id', 'tipos_plano_contas_id', 'ano', 'mes'],'idx_resumo_fluxo_caixa_emp_tipo_ano_mes' // <--- NOME DO ÍNDICE MAIS CURTO AQUI
);


            $table->index(['categoria_id', 'ano', 'mes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('resumo_fluxo_caixa');
    }
};
