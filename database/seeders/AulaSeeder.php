<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aula;
use App\Models\Categoria;

class AulaSeeder extends Seeder
{
    public function run()
    {
        $aulas = [
            ['Codigo' => 'A015', 'Nombre' => 'Aula 015', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Primera'],
            ['Codigo' => 'A016', 'Nombre' => 'Aula 016', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Primera'],
            ['Codigo' => 'A017', 'Nombre' => 'Aula 017', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Primera'],
            ['Codigo' => 'A018', 'Nombre' => 'Aula 018', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Primera'],
            ['Codigo' => 'A101', 'Nombre' => 'Aula 101', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A102', 'Nombre' => 'Aula 102', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A103', 'Nombre' => 'Aula 103', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A104', 'Nombre' => 'Aula 104', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A105', 'Nombre' => 'Aula 105', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A106', 'Nombre' => 'Aula 106', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A107', 'Nombre' => 'Aula 107', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A108', 'Nombre' => 'Aula 108', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A109', 'Nombre' => 'Aula 109', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A110', 'Nombre' => 'Aula 110', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A111', 'Nombre' => 'Aula 111', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A112', 'Nombre' => 'Aula 112', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A115', 'Nombre' => 'Aula 115', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A116', 'Nombre' => 'Aula 116', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A117', 'Nombre' => 'Aula 117', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Segunda'],
            ['Codigo' => 'A201', 'Nombre' => 'Aula 201', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A202', 'Nombre' => 'Aula 202', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A203', 'Nombre' => 'Aula 203', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A204', 'Nombre' => 'Aula 204', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A205', 'Nombre' => 'Aula 205', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A206', 'Nombre' => 'Aula 206', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A207', 'Nombre' => 'Aula 207', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A208', 'Nombre' => 'Aula 208', 'Capacidad' => 25, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A211', 'Nombre' => 'Aula Informática', 'Capacidad' => 20, 'Categoria' => 'Informática', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A212', 'Nombre' => 'Aula 212', 'Capacidad' => 15, 'Categoria' => 'Taller', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'A216', 'Nombre' => 'Laboratorio Física', 'Capacidad' => 15, 'Categoria' => 'Laboratorio', 'Edificio' => 'A', 'Planta' => 'Tercera'],
            ['Codigo' => 'AD01', 'Nombre' => 'Aula D01', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Bajo'],
            ['Codigo' => 'AD02', 'Nombre' => 'Aula D02', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Bajo'],
            ['Codigo' => 'AD11', 'Nombre' => 'Aula D11', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Bajo'],
            ['Codigo' => 'AD12', 'Nombre' => 'Aula D12', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Bajo'],
            ['Codigo' => 'AD21', 'Nombre' => 'Aula D21', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Bajo'],
            ['Codigo' => 'AD22', 'Nombre' => 'Aula D22', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Bajo'],
            ['Codigo' => 'AD23', 'Nombre' => 'Aula D23', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Bajo'],
            ['Codigo' => 'AMUL', 'Nombre' => 'Aula MUL', 'Capacidad' => 15, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Bajo'],
            ['Codigo' => 'AMUS', 'Nombre' => 'Aula MUS', 'Capacidad' => 15, 'Categoria' => 'Común', 'Edificio' => 'A', 'Planta' => 'Bajo'],
            ['Codigo' => 'BIB', 'Nombre' => 'Biblioteca', 'Capacidad' => 10, 'Categoria' => 'Biblioteca', 'Edificio' => 'A', 'Planta' => 'Bajo'],
            ['Codigo' => 'BIB2', 'Nombre' => 'Sala adjunta de la biblioteca', 'Capacidad' => 10, 'Categoria' => 'Biblioteca', 'Edificio' => 'A', 'Planta' => 'Bajo'],
            ['Codigo' => 'GIM', 'Nombre' => 'Gimnasio pequeño', 'Capacidad' => 20, 'Categoria' => 'Gimnasio', 'Edificio' => 'C', 'Planta' => 'Bajo'],
            ['Codigo' => 'GIMV', 'Nombre' => 'Gimnasio grande', 'Capacidad' => 40, 'Categoria' => 'Gimnasio', 'Edificio' => 'C', 'Planta' => 'Bajo'],
            ['Codigo' => 'I001', 'Nombre' => 'Aula 001', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Bajo'],
            ['Codigo' => 'I101', 'Nombre' => 'Aula 101', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Primera'],
            ['Codigo' => 'I102', 'Nombre' => 'Aula 102', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Primera'],
            ['Codigo' => 'I103', 'Nombre' => 'Aula 103', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Primera'],
            ['Codigo' => 'I201', 'Nombre' => 'Aula 201', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Segunda'],
            ['Codigo' => 'I202', 'Nombre' => 'Aula 202', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Segunda'],
            ['Codigo' => 'I203', 'Nombre' => 'Aula 203', 'Capacidad' => 20, 'Categoria' => 'Común', 'Edificio' => 'B', 'Planta' => 'Segunda'],
        ];

        foreach ($aulas as $aula) {
            $categoria = Categoria::where('nombre', $aula['Categoria'])->first();

            if ($categoria) {
                Aula::create([
                    'codigo' => $aula['Codigo'],
                    'nombre' => $aula['Nombre'],
                    'capacidad' => $aula['Capacidad'],
                    'categoria_id' => $categoria->id,
                    'edificio' => $aula['Edificio'],
                    'planta' => $aula['Planta'],
                ]);
            } else {
                \Log::warning('Categoría no encontrada para aula: ' . $aula['Codigo']);
            }
        }
    }
}
