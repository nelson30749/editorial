<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table="libros";
    protected $fillable = ['nombre','genero','grado','descripcion','stock','precio','estado'];
    public $timestamps = false;
}
