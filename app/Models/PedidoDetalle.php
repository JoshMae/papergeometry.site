<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $table = 'pedido_detalle';
    protected $primaryKey = 'idPedido_detalle';
    public $timestamps = false;

    protected $fillable = ['idPedido', 'idProducto', 'cantidad', 'subTotal'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto'); // Asumiendo que tienes un modelo Producto
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idPedido', 'idPedido');
    }
}
