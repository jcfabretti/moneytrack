<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::factory()->create([
            'nome' => 'Primeira Emrpresa',
            'grupo_economico_id' => 1, // Assuming the first group created
            'cod_fiscal' => '12345678901234',
            'localidade' => 'Neste Lugar',
            'tipos_planocontas_id' => 1 // Assuming the first type created
        ]);
    }
}
