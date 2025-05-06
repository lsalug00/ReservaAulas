<?php
// database/factories/AulaFactory.php

namespace Database\Factories;

use App\Models\Aula;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class AulaFactory extends Factory
{
    protected $model = Aula::class;

    public function definition(): array
    {
        $edificios = ['Edificio A', 'Edificio B', 'Edificio C'];
        $plantas = ['Planta Baja', 'Primera', 'Segunda'];

        return [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{1,3}[0-9]{2,3}'),
            'nombre' => 'Aula ' . $this->faker->bothify('###'),
            'capacidad' => $this->faker->numberBetween(20, 40),
            'categoria_id' => Categoria::inRandomOrder()->first()?->id,
            'edificio' => $this->faker->randomElement($edificios),
            'planta' => $this->faker->randomElement($plantas),
        ];
    }
}

