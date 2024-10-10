<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'idPedido';
    protected $fillable = ['idCliente', 'cart_token', 'total', 'idestado_pedido'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    public function pedidoDetalles()
    {
        return $this->hasMany(PedidoDetalle::class, 'idPedido', 'idPedido');
    }

    public function estadoPedido()
    {
        return $this->belongsTo(EstadoPedido::class, 'idestado_pedido', 'idestado_pedido');
    }
}
