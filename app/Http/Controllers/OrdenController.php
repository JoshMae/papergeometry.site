<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrdenController extends Controller
{
    //Metodo para mostrar la orden o resumen
    public function mostrarOrden(){

        $productosEnCarrito = session()->get('carrito', []); 
        $totalCarrito = 0;
        $subtotal = 0;
    
        foreach ($productosEnCarrito as $producto) {
            $subtotal += $producto['precio'] * $producto['cantidad'];
        }
        
        $totalCarrito = $subtotal; 
    
        return view('carrito.ordenar', compact('productosEnCarrito', 'totalCarrito', 'subtotal'));
    }

    private function calcularTotalCarrito($carrito){

        $total = 0;
        foreach($carrito as $producto){
        $total += $producto['precio']* $producto['cantidad'];
    }
    return $total;
}

//SIMULACION DE TARJETAS PARA PAGO DE PRUEBAS
public function procesarPago(Request $request)
    {
        // TARJETAS SIMULADORAS DE PRUEBA
        $tarjetasValidas = [
            [
                'numero' => '1234567890123456',
                'expiracion' => '12/25',
                'codigo_seguridad' => '123',
                'nombre' => 'Usuario Valido',
                'saldo' => 5000 
            ],
            [
                'numero' => '6543210987654321',
                'expiracion' => '11/23',
                'codigo_seguridad' => '456',
                'nombre' => 'Usuario Sin Saldo',
                'saldo' => 0 
            ]
        ];

        
        $numeroTarjeta = str_replace(' ', '', $request->input('tarjeta'));
        $expiracion = $request->input('expiracion');
        $codigoSeguridad = $request->input('codigo_seg');
        $nombreTitular = $request->input('nombre_tarjeta');
        $montoCompra = $request->input('totalCarrito'); // El total a pagar

        
        foreach ($tarjetasValidas as $tarjeta) {
            if ($tarjeta['numero'] == $numeroTarjeta &&
                $tarjeta['expiracion'] == $expiracion &&
                $tarjeta['codigo_seguridad'] == $codigoSeguridad &&
                $tarjeta['nombre'] == $nombreTitular) {
                
                if ($tarjeta['saldo'] >= $montoCompra) {
                    return response()->json(['status' => 'success', 'message' => 'Pago exitoso']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Saldo insuficiente']);
                }
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Datos de tarjeta inválidos']);
    }


    public function procesarPagoPos(Request $request)
    {


        Log::info('Datos recibidos de la API:', $request->all());

        // Obtener los datos del formulario
        $cuenta = $request->input('cuenta');
        $valor = $request->input('valor'); // Total de la compra
        $terminal = '1'; // Valor fijo para terminal
        $empresa = '1'; // Valor fijo para empresa

        // Log para verificar los valores
        Log::info('Número de cuenta:', ['cuenta' => $cuenta]);
        Log::info('Valor total:', ['valor' => $valor]);
        Log::info('Terminal:', ['terminal' => $terminal]);
        Log::info('Empresa:', ['empresa' => $empresa]);

        // Datos que espera la API
        $datos = [
            'cuenta' => $cuenta,
            'terminal' => $terminal,
            'valor' => $valor,
            'empresa' => $empresa,
        ];

        // URL de la API
        $urlApi = 'http://desarrollowebumg.somee.com/api/Principal/CobroPos';

        // Hacer la solicitud POST a la API
        $respuesta = Http::post($urlApi, $datos);

        // Verificar si la respuesta fue exitosa
        if ($respuesta->successful()) {
            // Obtener el resultado de la respuesta (1 o 0)
            $resultado = $respuesta->body();

            // Loguear el resultado en la consola
            Log::info('Respuesta de la API: ' . $resultado);
            
            // También puedes devolverlo en la respuesta de la API
            return response()->json(['status' => $resultado]);
        } else {
            // Si la API no responde o hay un error
            return response()->json(['status' => 'error', 'message' => 'Error al comunicarse con la API']);
        }
    }
    
    
}
