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
     Schema::create('valores_mensais', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT

            $table->unsignedBigInteger('empresa_id'); // FOREIGN KEY para a tabela de empresas
            $table->string('nome_empresa', 255); // Nome da empresa (desnormalizado para facilitar leitura)

            $table->string('tipo_dado', 50); // Ex: 'ContagemLancamentos', 'FluxoCaixa', 'FluxoCaixaCategoria', 'FluxoCaixaTotal'

            $table->unsignedBigInteger('item_id')->nullable(); // Adicionado para ID da categoria (ou null para totais gerais)
            $table->string('item_nome', 255)->nullable(); // Adicionado para Nome da categoria ou 'Total Geral'

            $table->integer('quantidade_numerica')->nullable(); // Para contagens (ex: total de lançamentos)
            $table->decimal('valor_monetario', 15, 2)->nullable(); // Para valores financeiros (ex: fluxo de caixa)

            $table->string('mes_ano', 7); // Formato 'MM/YYYY' (ex: '01/2025')

            $table->timestamps(); // created_at e updated_at

            // Chave estrangeira para a tabela 'empresas' (ajuste se sua tabela de empresas tem outro nome)
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');

            // Restrição de unicidade para garantir que não haja duplicatas para o mesmo tipo de dado, empresa, mês/ano e item_id
            $table->unique(['empresa_id', 'mes_ano', 'tipo_dado', 'item_id'], 'valores_mensais_unique_data_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valores_mensais');
    }
};
