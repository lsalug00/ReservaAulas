<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = ['codigo', 'nombre', 'capacidad', 'categoria_id', 'edificio', 'planta'];
    public $timestamps = false;

    // Relación con la categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación con HorariosClase
    public function horariosClases()
    {
        return $this->hasMany(HorariosClase::class);
    }

    // Relación con Reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
?>