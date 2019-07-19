<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $table= "plan_pagos";
    protected $filliable = ['idEntrega','fecha','fechaInicio','fechaFin','pago','montoTotal','estado'];
    public $timestamps = false;
}
