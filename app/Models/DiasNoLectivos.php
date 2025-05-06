<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiasNoLectivos extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion', 'fecha'];
    public $timestamps = false;

}
?>