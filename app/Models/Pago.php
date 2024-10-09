<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $primaryKey = 'idPago';
    protected $fillable = ['idBanco', 'idRespuesta'];
}
