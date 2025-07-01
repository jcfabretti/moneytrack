<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parceiro; // Importe o modelo

class ParceiroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar um parceiro padrão/específico (se necessário)
        Parceiro::create([
            'nome' => 'Parceiro Padrão',
            'nat_jur' => 'P.Fisica',
            'tipo_cliente' => 'Cliente',
            'cod_fiscal' => '00000000000', // CPF fictício
            'localidade' => 'Cidade Padrão',
            'status' => 1,
        ]);

        // 2. Criar parceiros usando os estados da factory:

        // Criar 3 bancos
        Parceiro::factory()->count(3)->banco()->create();

        // Criar 5 clientes pessoa física
        Parceiro::factory()->count(5)->clientePessoaFisica()->create();

        // Criar 2 clientes pessoa jurídica
        Parceiro::factory()->count(2)->clientePessoaJuridica()->create();

        // Criar 4 fornecedores
        Parceiro::factory()->count(4)->fornecedor()->create();

        // Criar 10 parceiros aleatórios (usará a lógica condicional do definition())
        Parceiro::factory()->count(10)->create();
    }
}
