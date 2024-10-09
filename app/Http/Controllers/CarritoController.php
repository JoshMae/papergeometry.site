<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Producto; 

class CarritoController extends Controller
{
    // Método para generar un token único para el carrito
    public function createCartToken()
    {
        $cartToken = Str::random(32);
        return response()->json(['cart_token' => $cartToken]);
    }

    // Método para agregar productos al carrito
    public function addToCart(Request $request)
        {
            $cartToken = $request->header('Cart-Token');

            if (!$cartToken) {
                return response()->json(['error' => 'Token no proporcionado'], 400);
            }

            $productId = $request->input('idProducto');
            $cantidad = $request->input('cantidad');

            // Valida si el producto existe en la tabla productos
            $product = Producto::find($productId);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
            }

            $subtotal = $product->precio * $cantidad;

            // Busca si el producto ya está en el carrito
            $carrito = Carrito::where('cart_token', $cartToken)
                              ->where('idProducto', $productId)
                              ->first();

            if ($carrito) {
                // Si el producto ya está en el carrito, actualiza la cantidad y el subtotal
                $carrito->cantidad += $cantidad;
                $carrito->subtotal += $subtotal;
                $carrito->save();
            } else {
                // Si el producto no está en el carrito, créalo
                $carrito = Carrito::create([
                    'cart_token' => $cartToken,
                    'idProducto' => $productId,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Producto agregado al carrito']);
        }


    // Método para obtener el carrito actual
    public function getCart(Request $request)
    {
        $cartToken = $request->header('Cart-Token');
        
        if (!$cartToken) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        // Recupera los productos del carrito asociados al token
        $carrito = Carrito::where('cart_token', $cartToken)
            ->with('producto')  // Asegúrate de tener una relación `product` en el modelo Carrito
            ->get();

        $total= 0;
        $carritoT= [];

        foreach($carrito as $item){
            $producto= Producto::find($item->idProducto);

            if($producto){
                $subtotal= $item->subtotal;
                $total+=$subtotal;

                $carritoT[] =[
                    'idCarrito' => $item->idCarrito,
                    'cantidad'  => $item->cantidad,
                    'producto'  => [
                        'idProducto'=> $producto->idProducto,
                        'nombre' => $producto->nombreP,
                        'precio' => $producto->precio,
                        'foto' => Storage::url($producto->foto),  
                    ],
                    'subtotal' => $subtotal

                ];
            }
        }

        if ($carrito->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Carrito vacío o no encontrado'], 200);
        }

        return response()->json([
            'success' => true, 
            'carrito' => $carrito,
            'total'   => $total,
        ]);
    }

    //Metodo para actualizar cantidad de carrito
    public function updateQuantity(Request $request, $idCarrito)
    {
        $carrito = Carrito::find($idCarrito);

        if ($carrito) {
            $nuevaCantidad = $request->input('cantidad');

            if ($nuevaCantidad < 1) {
                $carrito->delete();
                return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito']);
            }

            $carrito->cantidad = $nuevaCantidad;
            $carrito->subtotal = $carrito->producto->precio * $nuevaCantidad; 
            $carrito->save();

            return response()->json(['success' => true, 'message' => 'Cantidad actualizada', 'carrito' => $carrito]);
        } else {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado en el carrito']);
        }
    }

    // Método para eliminar productos del carrito
    public function removeFromCart($idCarrito, Request $request)
    {
        $cartToken = $request->header('Cart-Token');

        if (!$cartToken) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        $carrito = Carrito::where('cart_token', $cartToken)->where('idCarrito', $idCarrito)->first();

        if (!$carrito) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado en el carrito'], 404);
        }

        $carrito->delete();

        return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito']);
    }
}
