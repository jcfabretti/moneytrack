<?php

namespace Database\Factories;

use App\Models\GrupoEconomico; // Importe seu modelo
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Se precisar de strings aleatórias

    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GrupoEconomico>
     */
class GrupoEconomicoFactory extends Factory
    {
        /**
         * The name of the factory's corresponding model.
         *
       * @var class-string<\App\Models\GrupoEconomico>
         */
        protected $model = GrupoEconomico::class;

        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array
        {
            return [
                'nome' => $this->faker->company(), // Gera um nome de empresa fictício
                'localidade' => $this->faker->city(), // Cidade fictícia
            ];
        }
}
