<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEntrega extends Model
{
    protected $fillable = ['idEntrega','idLibro','cantidad','precio','estado'];
    public $timestamps = false;
}
