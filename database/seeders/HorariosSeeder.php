<?php

// database/seeders/HorariosSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorariosSeeder extends Seeder
{
    public function run(): void
    {
        $franjas = [
            // Turno de mañana
            ['hora_inicio' => '08:40', 'hora_fin' => '09:30', 'turno' => 'mañana'], //1
            ['hora_inicio' => '09:35', 'hora_fin' => '10:25', 'turno' => 'mañana'], //2
            ['hora_inicio' => '10:30', 'hora_fin' => '11:20', 'turno' => 'mañana'], //3
            ['hora_inicio' => '11:20', 'hora_fin' => '11:50', 'turno' => 'mañana'], //R3
            ['hora_inicio' => '11:50', 'hora_fin' => '12:40', 'turno' => 'mañana'], //4
            ['hora_inicio' => '12:45', 'hora_fin' => '13:35', 'turno' => 'mañana'], //5
            ['hora_inicio' => '13:40', 'hora_fin' => '14:30', 'turno' => 'mañana'], //6

            // Espacio entre turnos
            ['hora_inicio' => '14:30', 'hora_fin' => '15:25', 'turno' => 'ambos'], //7

            // Turno de tarde
            ['hora_inicio' => '15:25', 'hora_fin' => '16:15', 'turno' => 'tarde'], //V1
            ['hora_inicio' => '16:15', 'hora_fin' => '17:05', 'turno' => 'tarde'], //V2
            ['hora_inicio' => '17:05', 'hora_fin' => '17:55', 'turno' => 'tarde'], //V3
            ['hora_inicio' => '17:55', 'hora_fin' => '18:10', 'turno' => 'tarde'], //NO TIENE
            ['hora_inicio' => '18:10', 'hora_fin' => '19:00', 'turno' => 'tarde'], //V4
            ['hora_inicio' => '19:00', 'hora_fin' => '19:50', 'turno' => 'tarde'], //V5
            ['hora_inicio' => '19:50', 'hora_fin' => '20:40', 'turno' => 'tarde'], //V6
        ];

        foreach ($franjas as $franja) {
            Horario::firstOrCreate($franja);
        }

        // $this->command->info('✅ Franjas horarias insertadas correctamente.');
    }
}

