<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Categoria;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
 class CategoriaFactory extends Factory
    {
        protected $model = Categoria::class;

        public function definition(): array
        {
            return [
                'numero_categoria' => $this->faker->unique()->randomNumber(6), // Exemplo: 6 dígitos
                'nome' => $this->faker->word(),
                'categoria_pai' => $this->faker->numerify('######'),
                'nivel' => $this->faker->numberBetween(0, 3),
                'fk_tipocategoria_id' => \App\Models\CategoriaTipo::factory(), // Ou um ID de CategoriaTipo existente
            ];
        }
    }
