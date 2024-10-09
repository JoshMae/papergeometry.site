<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(): JsonResponse
    {
        // Se obtienen los productos con estado 1
        $productos = Producto::with('categoria')
            ->where('estado', 1)
            ->get();

        // Obtiene los datos en el formato deseado
        $result = $productos->map(function ($producto) {
            return [
                'idProducto' => $producto->idProducto,
                'nombre' => $producto->nombreP,
                'precio' => $producto->precio,
                'detalle' => $producto->detalleP,
                'categoria' => $producto->categoria->categoria, 
                'foto' => $producto->foto,
                'fecha_registro' => $producto->fecha_registro,
            ];
        });

        return response()->json($result);
    }
}

//Storage::url(