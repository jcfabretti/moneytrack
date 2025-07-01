<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // A ordem é importante devido às chaves estrangeiras!
        // Crie tabelas que outras dependem primeiro.

        $this->call([
            UserSeeder::class, // Cria usuários (necessário para created_by/updated_by)
            GrupoEconomicoSeeder::class, // Cria grupos (necessário para lançamentos)
            CategoriaTipoSeeder::class, // <<< Chame seu seeder de categorias aqui, se tiver
            EmpresaSeeder::class, // Cria empresas (necessário para lançamentos)
            ParceiroSeeder::class, // Cria parceiros (necessário para lançamentos)
            CategoriaSeeder::class, // Cria categorias (necessário para lançamentos)
        ]);


       }
}

