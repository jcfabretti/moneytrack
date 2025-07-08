<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GrupoEconomico;

class GrupoEconomicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GrupoEconomico::factory()->create([
            'nome' => 'Grupo Primeiro',
            'localidade' => 'Neste Lugar'
        ]);
    }
}
