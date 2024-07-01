<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserTypes;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        UserTypes::factory()->create([
            'name' => 'Administrador'
        ]);

        UserTypes::factory()->create([
            'name' => 'Cajero'
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('pass123'),
            'user_type' => 1,
        ]);

        User::factory()->create([
            'name' => 'Cajero 1',
            'email' => 'cajero@email.com',
            'password' => bcrypt('pass123'),
            'user_type' => 2,
        ]);

        $this->call([
            ProductsSeeder::class
        ]);
    }
}
