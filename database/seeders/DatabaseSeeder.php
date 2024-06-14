<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['name' => 'Test', 'email' => 'test@test.com', 'password' => bcrypt('test123')]);
        Cliente::factory(20000)->create();
        Producto::factory(10000)->create();
        Servicio::factory(10000)->create();
    }
}
