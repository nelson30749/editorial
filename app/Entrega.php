<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = ['nro','idPromotor','fecha','comprobante','cantidad','montoTotal','pago','estado'];
    public $timestamps = false;

}
