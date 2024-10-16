<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CarritoControllerNuevo;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\BienvenidaController;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\PedidoController;

/*Route::get('/', function () {
    return "HOLA MUNDO";
});*/



Route::get('/', [BienvenidaController::class, 'index'])->name('Bienvenida');

Route::get('/nuevo-carrito', [CarritoControllerNuevo::class, 'verCarrito'])->name('nuevoCarrito');
Route::post('/nuevo-carrito/agregar', [CarritoControllerNuevo::class, 'agregarAlCarrito'])->name('nuevoCarritoAgregar');
Route::post('/nuevo-carrito/aumentar/{id}', [CarritoControllerNuevo::class, 'aumentarCantidad'])->name('nuevoCarritoAumentar');
Route::post('/nuevo-carrito/disminuir/{id}', [CarritoControllerNuevo::class, 'disminuirCantidad'])->name('nuevoCarritoDisminuir');
Route::delete('/nuevo-carrito/eliminar/{id}', [CarritoControllerNuevo::class, 'eliminarProducto'])->name('nuevoCarritoEliminar');

//Ruta para procesarorden
Route::get('/ordenar', [OrdenController::class, 'mostrarOrden'])->name('carrito.ordenar');

Route::post('/api-proxy/cobropos', function (Illuminate\Http\Request $request) {
    // Recibe los datos del formulario y los envía a la API externa
    $response = Http::post('http://desarrollowebumg.somee.com/api/Principal/CobroPos', [
        'cuenta' => $request->input('cuenta'),
        'terminal' => $request->input('terminal'),
        'valor' => $request->input('valor'),
        'empresa' => $request->input('empresa')
    ]);

    // Retorna la respuesta de la API externa al frontend
    return $response->body();
});


Route::post('/api/procesar-pago', [OrdenController::class, 'procesarPago']);
Route::post('/api/procesar-pago-pos', [OrdenController::class, 'procesarPagoPos']);

Route::get('/estado_orden', [PedidoController::class, 'mostrarProgreso'])->name('barra-progreso');

//ADMINISTRADOR
//Notificar estado de pedido 
// web.php

Route::get('/administracion', function(){
    return view('layouts.administrador');
})->name('layout.administrador');

Route::get('/pedidos/{id}/detalle', [ContenidoController::class, 'detallePedido']);
Route::get('/cargar-vista/{vista}', [ContenidoController::class, 'cargarVista']);
Route::get('/cargar-contenido-inventario/{contenido}', [ContenidoController::class, 'cargarContenidoInventario']);


Route::post('/pedidos/{id}/siguiente_estado', [PedidoController::class, 'actualizarEstado']);
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::get('/pedidos/{id}', [PedidoController::class, 'show']); // Para mostrar detalles individuales

Route::post('pedido/{idPedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedido.cambiarEstado');
Route::get('pedido/{idPedido}', [PedidoController::class, 'mostrar'])->name('pedido.mostrar');
