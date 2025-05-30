<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'telefono'];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
