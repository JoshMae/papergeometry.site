<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';

    // Especifica que la clave primaria es 'idProducto'
    protected $primaryKey = 'idProducto';
    protected $keyType = 'int';

    protected $fillable = [
        'nombreP',
        'precio',
        'detalleP',
        'idCategoria',
        'foto',
        'fecha_registro',
        'estado',
    ];

    // Relación con la tabla 'categoria'
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
    } 
}

