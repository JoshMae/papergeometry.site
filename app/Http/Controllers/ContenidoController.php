<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class ContenidoController extends Controller
{
    public function cargarVista($vista)
    {
        if (view()->exists("admin.$vista")) {
            return view("admin.$vista");
        }

        return '<p>La vista solicitada no existe.</p>';
    }

    public function cargarContenidoInventario($contenido)
    {
        if (view()->exists("inventario.$contenido")) {
            return view("inventario.$contenido");
        }
        return '<p>Contenido de inventario no especificado.</p>';
    }

    public function detallePedido($id)
    {
        return view('admin.detalle_pedido', ['idPedido' => $id]);
    }

    /* public function detallePedido($id)
    {
        $pedido = Pedido::with('cliente', 'pedidoDetalles.producto')
        ->where('idPedido', $id)
        ->firstOrFail();

        return view('admin.detalle_pedido', 'pedido');
    } */
}
