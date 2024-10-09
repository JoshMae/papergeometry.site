<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'idPedido';
    protected $fillable = ['idCliente', 'total', 'idPago'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class, 'idPedido');
    }
}
