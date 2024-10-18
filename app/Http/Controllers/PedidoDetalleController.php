<?php

namespace App\Http\Controllers;

use App\Models\PedidoDetalle;
use Illuminate\Http\Request;

class PedidoDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Recuperar los detalles del pedido incluyendo la información del producto
        $detalles = PedidoDetalle::where('idPedido', $id)
            ->with('producto')
            ->get();

        // Calcular el total gastado
        $totalGastado = $detalles->sum('subTotal');

        // Formatear los datos para el JSON de respuesta
        $respuesta = [
            'idPedido' => $id,
            'productos' => $detalles->map(function ($detalle) {
                return [
                    'nombre' => $detalle->producto->nombreP,
                    'imagen' => $detalle->producto->foto, 
                    'cantidad' => $detalle->cantidad,
                    'subTotal' => $detalle->subTotal,
                ];
            }),
            'total_gastado' => $totalGastado,
        ];

        // Retornar el JSON
        return response()->json($respuesta);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
