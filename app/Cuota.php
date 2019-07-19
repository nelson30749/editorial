<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table= "coutas";
    protected $filliable = ['nro','idPlan','fecha','monto','estado'];
    public $timestamps = false;
}
