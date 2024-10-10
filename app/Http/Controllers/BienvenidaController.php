<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class BienvenidaController extends Controller
{
    public function index()
    {
        // Mensaje de bienvenida y subproductos en oferta
        $mensajebienvendida = "BIENVENIDOS A PAPER GEOMETRY";
        $subproductosenoferta = "Productos en Oferta";
        
        // Consume la API externa
        $response = Http::get('http://papergeometry.site/api/producto');

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            $productos = $response->json(); // Obtiene los productos
        } else {
            $productos = []; // Retorna un array vacío si no fue exitosa
        }

        // Pasa los datos a la vista de bienvenida
        return view('Bienvenida.index', compact('mensajebienvendida', 'subproductosenoferta', 'productos'));
    }

    
}