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
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->id()->autoIncrement();;

            $table->bigInteger('empresa_id')->unsigned()->nullable(false);
            $table->foreign('empresa_id')->references('id')->on('empresas');
            
            $table->bigInteger('grupo_economico_id')->unsigned()->nullable(false);
            $table->foreign('grupo_economico_id')->references('id')->on('grupo_economicos');

            $table->date('data_lcto');
            $table->string('tipo_docto',6)->nullable(false);
            $table->string('numero_docto',6)->nullable(false);
           
            $table->enum('tipo_conta', ['banco', 'cliente', 'fornecedor']);

            $table->bigInteger('conta_partida')->unsigned()->nullable(false);
            $table->foreign('conta_partida')->references('id')->on('parceiros');

            $table->bigInteger('categorias_id')->unsigned()->nullable(false);
            $table->foreign('categorias_id')->references('numero_categoria')->on('categorias');
           
            $table->bigInteger('conta_contrapartida')->unsigned()->nullable(false);
            $table->foreign('conta_contrapartida')->references('id')->on('parceiros');
            
            $table->string('historico',20)->nullable(false);
            $table->string('unidade',15)->nullable(false);
            $table->float('quantidade',4)->nullable();

            $table->double('valor,2')->nullable(false);
            
            $table->string('centro_custo',20)->nullable();
            $table->date('vencimento')->nullable();

            $table->string('origem',10)->nullable();
            $table->timestamps();
            $table->bigInteger('created_by')->unsigned()->nullable(); // Certifique-se que é unsigned
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null'); // Use set null se for nullable

            $table->bigInteger('updated_by')->unsigned()->nullable(); // Certifique-se que é unsigned
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null'); // Use set null se for nullable
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamentos');
    }
};
