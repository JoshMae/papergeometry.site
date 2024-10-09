<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Método para devolver todas las categorías
    public function getCategorias()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    // Método para devolver productos por categoría
    public function getProductosPorCategoria($idCategoria)
    {
        // Buscar la categoría por idCategoria
        $categoria = categoria::where('idCategoria', $idCategoria)->first();

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        // Obtener los productos de esa categoría
        $productos = producto::where('idCategoria', $idCategoria)->get();

        if ($productos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron productos en esta categoría'], 404);
        }

     return response()->json([
            'categoria' => $categoria,
            'productos' => $productos
        ]);
    }

    public function mostrarProductosPorCategoria($idCategoria)
    {
        // Obtener la respuesta de la API
        $respuesta = $this->getProductosPorCategoria($idCategoria);
    
        // Verificar si la respuesta es un JSON
        if ($respuesta instanceof \Illuminate\Http\JsonResponse) {
            if ($respuesta->getStatusCode() == 404) {
                return view('errors.404', ['message' => 'Categoría o productos no encontrados']);
            }
        }
    
        // Obtener los datos de la respuesta JSON
        $data = $respuesta->getData();
    
        // Devolver la vista con la categoría y los productos
        return view('productos.ProductoCategoria', [
            'categoria' => $data->categoria,
            'productos' => $data->productos
        ]);
    }
        
}
