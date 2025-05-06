<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorariosClase extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'aula_id', 'dia', 'hora_inicio', 'hora_fin'];
    public $timestamps = false;

    // Relación con el modelo User (Profesor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el modelo Aula
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}
?>