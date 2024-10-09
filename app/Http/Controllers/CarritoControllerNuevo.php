<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CarritoControllerNuevo extends Controller
{
    // Ver el carrito
    public function verCarrito()
    {
        $token = session()->get('cart_token');

        if (!$token) {
            $token = bin2hex(random_bytes(16)); 
            session()->put('cart_token', $token);
        } 

    // Obtener los productos del carrito 
    $productosEnCarrito = session()->get('carrito', []);

    // Verificar si el carrito está vacío
    $carritoVacio = empty($productosEnCarrito);

    // Calcular el total del carrito
    $totalCarrito = collect($productosEnCarrito)->sum(function($producto) {
        return $producto['precio'] * $producto['cantidad'];
    });

    // Pasar variables a la vista
    return view('carrito.nuevoCarrito', [
        'productosEnCarrito' => $productosEnCarrito,
        'carritoVacio' => $carritoVacio,
        'totalCarrito' => $totalCarrito,
    ]);
    }

    // Agregar productos al carrito
    public function agregarAlCarrito(Request $request)
{
    // Obtener el carrito de la sesión
    $carrito = session()->get('carrito', []);

    // Verificar si existe el token en la sesión o generar uno
    $token = session()->get('cart_token', bin2hex(random_bytes(16)));
    session()->put('cart_token', $token);

    // Crear un nuevo producto con los datos del request
    $nuevoProducto = [
        'id' => $request->input('producto_id'),
        'nombre' => $request->input('nombre'),
        'precio' => $request->input('precio'),
        'foto' => $request->input('foto'),
        'cantidad' => $request->input('cantidad', 1),
    ];

    // Verificar si el producto ya existe en el carrito y actualizar la cantidad
    $productoExistente = false;
    foreach ($carrito as &$producto) {
        if ($producto['id'] == $nuevoProducto['id']) {
            $producto['cantidad'] += $nuevoProducto['cantidad'];
            $productoExistente = true;
            break;
        }
    }

    // Si el producto no existe, agregarlo al carrito
    if (!$productoExistente) {
        $carrito[] = $nuevoProducto;
    }

    // Guardar el carrito en la sesión
    session()->put('carrito', $carrito);

    // Calcular el subtotal del producto (precio * cantidad)
    $subtotal = $nuevoProducto['precio'] * $nuevoProducto['cantidad'];

    // Insertar los datos en la base de datos
    DB::table('carrito')->insert([
        'cart_token' => $token,
        'idProducto' => $nuevoProducto['id'],
        'cantidad' => $nuevoProducto['cantidad'],
        'subtotal' => $subtotal,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Responder con un mensaje de éxito
    return response()->json(['message' => 'Producto agregado al carrito con éxito'], 200);
}


    // Aumentar cantidad de un producto en el carrito
    public function aumentarCantidad($id)
    {
        $carrito = session()->get('carrito', []);
        foreach ($carrito as &$producto) {
            if ($producto['id'] == $id) {
                $producto['cantidad']++;
                break;
            }
        }
        session()->put('carrito', $carrito);

        return response()->json([
            'productosEnCarrito' => $carrito,
            'totalCarrito' => $this->calcularTotalCarrito($carrito)
        ]);
    }

    // Disminuir cantidad de un producto en el carrito
    public function disminuirCantidad($id)
    {
        $carrito = session()->get('carrito', []);
        foreach ($carrito as &$producto) {
            if ($producto['id'] == $id && $producto['cantidad'] > 1) {
                $producto['cantidad']--;
                break;
            }
        }
        session()->put('carrito', $carrito);

        return response()->json([
            'productosEnCarrito' => $carrito,
            'totalCarrito' => $this->calcularTotalCarrito($carrito)
        ]);
    }

    // Eliminar un producto del carrito
    public function eliminarProducto($id)
    {
        $carrito = session()->get('carrito', []);
        foreach ($carrito as $key => $producto) {
            if ($producto['id'] == $id) {
                unset($carrito[$key]);
                break;
            }
        }
        session()->put('carrito', $carrito);
    
        // Determinar si el carrito está vacío
        $carritoVacio = empty($carrito);
    
        return response()->json([
            'productosEnCarrito' => $carrito,
            'totalCarrito' => $this->calcularTotalCarrito($carrito),
            'carritoVacio' => $carritoVacio, // Retornar el estado del carrito vacío
        ]);
    }
    


    // Calcular el total del carrito
    private function calcularTotalCarrito($carrito)
    {
        $total = 0;
        foreach ($carrito as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }
        return $total;
    }
}
