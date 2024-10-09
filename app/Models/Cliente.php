<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'idCliente';
    protected $fillable = ['nombres', 'apellidos', 'telefono', 'correo'];
}
