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
            Schema::create('parceiros', function (Blueprint $table) {
                $table->id()->autoIncrement(); 
                $table->string('nome')->unique();
                $table->enum('nat_jur', ['P.Juridica', 'P.Fisica']);
                $table->enum('tipo_cliente', ['Banco', 'Cliente', 'Fornecedor']);
                $table->string('cod_fiscal')->unique();
                $table->string('localidade');
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parceiros');
    }
};
