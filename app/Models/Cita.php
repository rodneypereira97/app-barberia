<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\User;





class Cita extends Model
{
    protected $fillable = ['cliente_id', 'servicio_id', 'user_id', 'fecha', 'hora', 'estado'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
