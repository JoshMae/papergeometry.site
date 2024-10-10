<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\PedidoDetalle;
use App\Models\RegistroCambio;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    // Registrar una compra
    public function registrarCompra(Request $request)
    {
        /* $cart_token = session('cart_token'); */

        DB::beginTransaction();
        try {
            // Crear o encontrar el cliente
            $cliente = Cliente::firstOrCreate(
                ['correo' => $request->input('correo')],
                [
                    'nombres' => $request->input('nombres'),
                    'apellidos' => $request->input('apellidos'),
                    'telefono' => $request->input('telefono')
                ]
            );

            // Crear un nuevo pago
            $pago = Pago::create([
                'idBanco' => 1,
                'idRespuesta' => $request->input('respuesta') + 1
            ]);

            // Crear el pedido
            $pedido = Pedido::create([
                'idCliente' => $cliente->idCliente,
                'cart_token' => $request->input('token'),
                'total' => $request->input('total'),
                'idPago' => $pago->idPago
            ]);

            // Registrar detalles del pedido
            foreach ($request->input('detalles') as $detalle) {
                PedidoDetalle::create([
                    'idPedido' => $pedido->idPedido,
                    'idProducto' => $detalle['idProducto'],
                    'cantidad' => $detalle['cantidad'],
                    'subTotal' => $detalle['subTotal']
                ]);
            }

            // Registrar cambio de estado
            RegistroCambio::create([
                'idPedido' => $pedido->idPedido,
                'idEstado_pedido' => 1, // Estado inicial: Recibido
                'idEmpleado' => null // Puedes dejarlo nulo por ahora
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Compra registrada exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Ver pedido y detalles
    public function verPedido($id)
    {
        // Obtener el pedido junto con el cliente y los detalles
        $pedido = Pedido::with(['cliente', 'detalles', 'detalles.producto'])->find($id);

        if (!$pedido) {
            return response()->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        return response()->json(['success' => true, 'pedido' => $pedido], 200);
    }

    public function verPedidos()
    {
        // Obtener el pedido junto con el cliente y los detalles
        $pedido = Pedido::with(['cliente', 'detalles', 'detalles.producto'])->get();

        if (!$pedido) {
            return response()->json(['success' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        return response()->json(['success' => true, 'pedido' => $pedido], 200);
    }
}
