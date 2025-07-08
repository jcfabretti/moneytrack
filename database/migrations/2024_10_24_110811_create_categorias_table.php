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
        Schema::create('categorias', function (Blueprint $table) {
            $table->unsignedBigInteger('numero_categoria', false)->primary(); 
            $table->string('nome',25);
            $table->unsignedBigInteger('categoria_pai')->nullable(); // Permite nulo para categorias de nível superior
            $table->integer('nivel');
            $table->unsignedBigInteger('fk_tipocategoria_id');
            $table->foreign('fk_tipocategoria_id')->references('id')->on('colecao_categorias');
            $table->timestamps();
        });
    }

    /**
     * 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
