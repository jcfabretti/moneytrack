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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nome',40);
            $table->bigInteger('grupo_economico_id')->unsigned();
            $table->foreign('grupo_economico_id')->references('id')->on('grupo_economicos');
            $table->string('cod_fiscal',14);
            $table->string('localidade',30);
            $table->unsignedBigInteger('tipos_planocontas_id');
            $table->foreign('tipos_planocontas_id')->references('id')->on('colecao_categorias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
