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
        User::factory()->create(['name' => 'Test', 'email' => 'test@test.com', 'password' => bcrypt('test123'), 'role' => 'superadmin']);
        Cliente::factory(200)->create();
        Producto::factory(100)->create();
        Servicio::factory(100)->create();
    }
}
