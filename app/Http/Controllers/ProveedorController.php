<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proveedor = Proveedor::all();
        return $proveedor;
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $proveedor = new Poveedor();
        $proveedor->nombre = $request->nombre;
        $proveedor->telefono = $request->telefono;
        $proveedor->direccion = $request->direccion;
        $proveedor->estado = '1';
        $proveedor->save();
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $proveedor = Poveedor::findOrFail($request->id);
        $proveedor->nombre = $request->nombre;
        $proveedor->telefono = $request->telefono;
        $proveedor->direccion = $request->direccion;
        $proveedor->estado = '1';
        $proveedor->save();
    }

    public function desactivar(Request $request)
    {
        $proveedor = Poveedor::findOrFail($request->id);
        $proveedor->estado = '0';
        $proveedor->save();
    }

    public function activar(Request $request)
    {
        $proveedor = Poveedor::findOrFail($request->id);
        $proveedor->estado = '1';
        $proveedor->save();
    }


    
}
