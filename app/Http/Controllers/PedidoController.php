<?php

namespace App\Http\Controllers;

use App\Events\PedidoEstadoActualizado;
use App\Events\PedidoStatusUpdated;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        // Obtener los pedidos con la relación a cliente y estado_pedido
        $pedidos = Pedido::with(['cliente', 'estadoPedido'])
                        ->where('idestado_pedido', '<', 5)
                        ->get();

        // Estructurar la respuesta JSON
        $pedidosData = $pedidos->map(function ($pedido) {
            return [
                'id' => $pedido->idPedido,
                'cliente' => $pedido->cliente->nombres . ' ' . $pedido->cliente->apellidos,
                'estado' => $pedido->estadoPedido->nombre_estado, // Suponiendo que el nombre del estado está en el campo 'nombre'
            ];
        });

        return response()->json($pedidosData);
    }

    public function pedidoToken(Request $request) {
        
        $request->validate([
            'token' => 'required|string',
        ]);
    
        
        //$pedido = Pedido::where('cart_token', $request->input('token'))->first();
        $pedido = Pedido::where('cart_token', $request->input('token'))
                ->where('idestado_pedido', '<', 6)
                ->first();
    
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }
    
        return response()->json($pedido);
    }
    

    public function show($id)
    {
        $pedido = Pedido::with('cliente', 'pedidoDetalles.producto', 'estadoPedido')->findOrFail($id);

        return response()->json($pedido);
    }

    public function actualizarEstado(Request $request, $id)
    {
        // Encontrar el pedido
        $pedido = Pedido::findOrFail($id);
        
        // Actualizar el estado del pedido
        $pedido->idestado_pedido = $pedido->idestado_pedido + 1;
        Log::info('Estado: '.$pedido->idestado_pedido);
        $pedido->save();
        
        // Cargar el nuevo estado del pedido
        $pedidoConEstado = $pedido->load('estadoPedido');
        
        // Disparar el evento para notificar en tiempo real
        event(new PedidoEstadoActualizado([
            'idPedido' => $pedido->idPedido,
            'nuevo_estado' => $pedidoConEstado->estadoPedido->nombre_estado
        ]));
        
        // Responder con el nuevo estado
        return response()->json($pedidoConEstado);
    }

/* 
    public function cambiarEstado(Request $request, $idPedido)
    {
        // Validación
        $request->validate([
            'idEstado' => 'required|exists:estado_pedido,id',
        ]);

        // Obtener el pedido
        $pedido = Pedido::findOrFail($idPedido);

        // Registrar el cambio en registro_cambio
        RegistroCambio::create([
            'idPedido' => $pedido->idPedido,
            //'idEmpleado' => auth()->id() ?? null, // Si es null, se guarda null
            'idEmpleado' => null,
            'idEstado_pedido' => $request->input('idEstado'),
        ]);

        // Actualizar el estado del pedido
        $pedido->idEstado = $request->input('idEstado');
        $pedido->save();

        // Disparar el evento de actualización de estado
        event(new PedidoStatusUpdated($pedido));

        // Redirigir con éxito
        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }
 */
    public function mostrarProgreso()
    {
        // Obtener el cart_token desde la sesión del cliente
        $cart_token = session('cart_token');

        // Verificar si hay un cart_token en la sesión
        if (!$cart_token) {
            return redirect('/nuevo-carrito')->with('error', 'No se encontró ningún pedido asociado a este carrito.');
        }

        // Buscar el pedido asociado al cart_token
        $pedido = Pedido::where('cart_token', $cart_token)
                ->where('idestado_pedido', '<', 6)
                ->first();

        // Si no hay pedido para el cart_token, redirigir al carrito con un error
        if (!$pedido) {
            return redirect('/nuevo-carrito')->with('error', 'No se ha encontrado ningún pedido para este carrito.');
        }

        // Mostrar la vista de barra de progreso con el pedido encontrado
        return view('carrito.barraprogreso', compact('pedido'));
    }
}
