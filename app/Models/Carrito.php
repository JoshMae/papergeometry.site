<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carrito'; 
    protected $primaryKey = 'idCarrito'; 
    public $timestamps = true; 

    protected $fillable = [
        'cart_token', 
        'idProducto', 
        'cantidad',
        'subtotal'
    ];

    // Definir la relación con el modelo Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto');
    }
}