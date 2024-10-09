<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroCambio extends Model
{
    protected $table = 'registro_cambio';
    protected $primaryKey = 'idRegistro_cambio';
    protected $fillable = ['idPedido', 'idEmpleado', 'idEstado_pedido'];
}
