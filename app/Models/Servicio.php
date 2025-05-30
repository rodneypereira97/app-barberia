<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Servicio extends Model
{
    protected $fillable = ['nombre', 'precio', 'duracion'];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
