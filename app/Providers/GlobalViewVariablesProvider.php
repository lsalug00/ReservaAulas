<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class GlobalViewVariablesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // 🔧 Valores fijos para pruebas
        // $hoy = '2025-05-01';
        // $ahora = '10:30';
        
        //Valores reales
        $hoy = date('Y-m-d');
        $ahora = date('H:i');
        
        $dow = date('w', strtotime($hoy));
        // Si sábado o domingo → próxima semana, si no → actual
        $inicioTimestamp = $dow == 0 || $dow == 6
            ? strtotime('monday next week', strtotime($hoy))
            : strtotime('monday this week', strtotime($hoy));

        $inicio_semana = date('Y-m-d', $inicioTimestamp);
        $fin_semana_2 = date('Y-m-d', strtotime('+2 weeks sunday', $inicioTimestamp));

        View::share([
            'hoy' => $hoy,
            'ahora' => $ahora,
            'esFinde' => $dow == 0 || $dow == 6,
            'inicio_semana' => $inicio_semana,
            'fin_semana_2' => $fin_semana_2,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
