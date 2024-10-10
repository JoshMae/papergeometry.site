<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class CatalogoController extends Controller
{
        public function index()
        {
                //Aqui se consume la api
                $response = Http::get('http://papergeometry.site/api/producto');
                
                //Verifica si la solisitud a la Api fue exitosa
                if($response -> successful()){
                        $productos = $response-> json();
                } else {
                        $productos = [];
                }        
                
                
                //envia los datos a la vista
                return view('productos.index', ['productos' => $productos]);
         }
}