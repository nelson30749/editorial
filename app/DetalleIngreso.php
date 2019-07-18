<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $fillable = ['idIngreso','idLibro','cantidad','precio','estado'];
    public $timestamps = false;
}
