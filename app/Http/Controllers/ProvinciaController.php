<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Provincia;

class ProvinciaController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        if($buscar=='')
        {
            $provincias= Provincia::join('departamentos','provincias.idDpto','=','departamentos.id')
            ->select('provincias.id','departamentos.nombre as departamento','provincias.nombre as provincia','provincias.estado')
            ->where('provincias.estado','=','1')
            ->orderBy('provincias.id','desc')
            ->paginate(10);
        }
        else{
            $provincias= Provincia::join('departamentos','provincias.idDpto','=','departamentos.id')
            ->select('provincias.id','idDpto','departamentos.nombre as departamento','provincias.nombre as provincia','provincias.estado')
            ->where('provincias.nombre','like'.'%'.$buscar.'%')
            ->where('provincias.estado','=','1')
            ->orderBy('provincias.id','desc')->paginate(10);
            
        }
     
        return [
            'pagination' => [
                'total'        => $provincias->total(),
                'current_page' => $provincias->currentPage(),
                'per_page'     => $provincias->perPage(),
                'last_page'    => $provincias->lastPage(),
                'from'         => $provincias->firstItem(),
                'to'           => $provincias->lastItem(),
            ],
            'provincias' => $provincias
        ];
    }

    public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            $provincia=new Provincia();
            $provincia->idDpto = $request->idDpto;
            $provincia->nombre = $request->nombre;
            $provincia->estado = '1';
            $provincia->save();
        }
        public function update(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            $provincia=Provincia::findOrFail($request->id);
            $provincia->idDpto = $request->idDpto;
            $provincia->nombre = $request->nombre;
            $provincia->estado = '1';
            $provincia->save();
        }
        public function desactivar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $provincia = Provincia::findOrFail($request->id);
            $provincia->estado = '0';
            $provincia->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $provincia = Provincia::findOrFail($request->id);
            $provincia->estado = '1';
            $provincia->save();
        }
    }

