<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Cliente;
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
        User::factory()->create([
            'name' => 'Carla Ibañez Martinez',
            'email' => 'carlaayacor@pelu.com',
            'nickname' => 'test',
            'password' => bcrypt('carla123'),
            'role' => 'superadmin'
        ]);
    }
}
