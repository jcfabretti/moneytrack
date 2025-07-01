<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->company(),
            'grupo_economico_id' => 1, // Assuming the first group created
            'cod_fiscal' =>  $this->faker->numerify('##############'),
            'localidade' => $this->faker->city(),
            'tipos_planocontas_id' => $this->faker->numerify('#'),
        ];
    }
}
