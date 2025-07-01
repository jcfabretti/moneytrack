<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Mario',
            'email' => 'mario@mtrack.com',
            'password' => Hash::make('Mario123')
        ]);
         User::factory()->create([
            'name' => 'Carlos',
            'email' => 'carlos@mtrack.com',
            'password' =>  Hash::make('Carlos654'),
        ]);
    }
}
