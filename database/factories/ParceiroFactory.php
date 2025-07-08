<?php

namespace Database\Factories;

use App\Models\Parceiro;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Parceiro>
 */
class ParceiroFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Parceiro>
     */
    protected $model = Parceiro::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define um tipo_cliente padrão aleatório para a factory base
        // Este será o valor inicial antes que qualquer estado seja aplicado.
        $tipoCliente = $this->faker->randomElement(['Banco', 'Cliente', 'Fornecedor']);

        return [
            'nome' => ($tipoCliente === 'P.Juridica' || $tipoCliente === 'Banco' || $tipoCliente === 'Fornecedor')
                        ? $this->faker->company() // Se for pessoa jurídica/banco/fornecedor, use nome de empresa
                        : $this->faker->name(),    // Caso contrário, use nome de pessoa
            'nat_jur' => $this->faker->randomElement(['P.Juridica', 'P.Fisica']),
            'tipo_cliente' => $tipoCliente, // O tipo de cliente base
            'cod_fiscal' => $this->faker->unique()->numerify('##############'), // CNPJ/CPF fictício
            'localidade' => $this->faker->city(),
            'status' => $this->faker->boolean(),
        ];
    }

    /**
     * Define um estado para Parceiros do tipo 'Banco'.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function banco(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'nome' => $this->faker->unique()->randomElement([
                    'Banco do Brasil', 'Itaú Unibanco', 'Bradesco', 'Santander', 'Caixa Econômica Federal'
                ]),
                'nat_jur' => 'P.Juridica',
                'tipo_cliente' => 'Banco',
                'cod_fiscal' => $this->faker->unique()->numerify('##############'), // CNPJ
            ];
        });
    }

    /**
     * Define um estado para Parceiros do tipo 'Cliente' (pessoa física).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function clientePessoaFisica(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'nome' => $this->faker->name(),
                'nat_jur' => 'P.Fisica',
                'tipo_cliente' => 'Cliente',
                'cod_fiscal' => $this->faker->unique()->numerify('###########'), // CPF
            ];
        });
    }

    /**
     * Define um estado para Parceiros do tipo 'Cliente' (pessoa jurídica).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function clientePessoaJuridica(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'nome' => $this->faker->company(),
                'nat_jur' => 'P.Juridica',
                'tipo_cliente' => 'Cliente',
                'cod_fiscal' => $this->faker->unique()->numerify('##############'), // CNPJ
            ];
        });
    }

    /**
     * Define um estado para Parceiros do tipo 'Fornecedor' (pessoa jurídica).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function fornecedor(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'nome' => $this->faker->company(),
                'nat_jur' => 'P.Juridica',
                'tipo_cliente' => 'Fornecedor',
                'cod_fiscal' => $this->faker->unique()->numerify('##############'), // CNPJ
            ];
        });
    }
}
