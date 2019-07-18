<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departamento;

class DepartamentoController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        if($buscar=='')
        {
            $departamentos= Departamento::where('departamentos.estado','=','1')
            ->orderBy('departamentos.id','desc')
            ->paginate(10);
        }
        else{
            $departamentos= Departamento::where('departamentos.nombre','like'.'%'.$buscar.'%')
            ->where('departamentos.estado','=','1')
            ->orderBy('departamentos.id','desc')->paginate(10);
            
        }
     
        return [
            'pagination' => [
                'total'        => $departamentos->total(),
                'current_page' => $departamentos->currentPage(),
                'per_page'     => $departamentos->perPage(),
                'last_page'    => $departamentos->lastPage(),
                'from'         => $departamentos->firstItem(),
                'to'           => $departamentos->lastItem(),
            ],
            'departamentos' => $departamentos
        ];
    }

    public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            $departamento=new Departamento();
            $departamento->nombre = $request->nombre;
            $departamento->estado = '1';
            $departamento->save();
        }
        public function desactivar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $departamento = Departamento::findOrFail($request->id);
            $departamento->estado = '0';
            $departamento->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $departamento = Departamento::findOrFail($request->id);
            $departamento->estado = '1';
            $departamento->save();
        }
    }

