<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $filliable = ['idEntrega','fecha','fechaInicio',
    'fechaFin','pago','montoTotal'];
    public $timestamps = false;
}
