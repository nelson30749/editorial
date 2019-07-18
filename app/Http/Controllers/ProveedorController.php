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
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar=$request->buscar;
        $criterio=$request->criterio;
        if($buscar=='')
        {
        $proveedores = Proveedor::where('estado','=','1')
        ->orderBy('id','desc')
        ->paginate(10);
        }
        else{
            $proveedores= Promotor::where('proveedores.nombre','like','%'.$buscar.'%')
            ->where('apellido','like','%'.$buscar.'%')
            ->where('estado','=','1')
            ->orderBy('id','desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'        => $proveedores->total(),
                'current_page' => $proveedores->currentPage(),
                'per_page'     => $proveedores->perPage(),
                'last_page'    => $proveedores->lastPage(),
                'from'         => $proveedores->firstItem(),
                'to'           => $proveedores->lastItem(),
            ],
            'proveedores' => $proveedores
        ];
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $proveedor = new Proveedor();
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
        if(!$request->ajax()) return redirect('/');
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->nombre = $request->nombre;
        $proveedor->telefono = $request->telefono;
        $proveedor->direccion = $request->direccion;
        $proveedor->estado = '1';
        $proveedor->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->estado = '0';
        $proveedor->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->estado = '1';
        $proveedor->save();
    }


    
}
