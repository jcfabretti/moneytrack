<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::factory()->create([
            'numero_categoria' => '100000',
            'nome' => 'SALDO INICIAL',
            'categoria_pai' => '0',
            'nivel' => 0,
            'fk_tipocategoria_id' => 1,
        ]);
        Categoria::factory()->create([
            'numero_categoria' => '199999',
            'nome' => 'SEM CATEGORIA',
            'categoria_pai' => '0',
            'nivel' => 0,
            'fk_tipocategoria_id' => 1,
        ]);
         Categoria::factory()->create([
            'numero_categoria' => '110000',
            'nome' => 'ENTRADAS',
            'categoria_pai' => '0',
            'nivel' => 1,
            'fk_tipocategoria_id' => 1,
        ]);
        Categoria::factory()->create([
            'numero_categoria' => '120000',
            'nome' => 'SAIDAS',
            'categoria_pai' => '0',
            'nivel' => 1,
            'fk_tipocategoria_id' => 1,
        ]);
        Categoria::factory()->create([
            'numero_categoria' => '130000',
            'nome' => 'INVESTIMENTOS',
            'categoria_pai' => '0',
            'nivel' => 1,
            'fk_tipocategoria_id' => 1,
        ]); 

    }
}