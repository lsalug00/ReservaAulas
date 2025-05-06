<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'email' => 'admin@aulas.test',
            'name' => 'Admin',
            'surname' => '',
            'password' => bcrypt('admin1234'),
            'rol' => 'admin',
        ]);

        $this->call([
            CategoriaSeeder::class,
            AulaSeeder::class,
            HorariosSeeder::class,
        ]);
    }
}
