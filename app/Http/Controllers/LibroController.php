<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libro;

class LibroController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio=$request->criterio;
        if($buscar=='')
        {
            $libros= Libro::where('libros.estado','=','1')
            ->orderBy('libros.id','desc')
            ->paginate(10);
        }
        else{
            $libros= Libro::where('libros.'.$criterio,'like'.'%'.$buscar.'%')
            ->where('libros.estado','=','1')
            ->orderBy('libros.id','desc')->paginate(10);
            
        }
     
        return [
            'pagination' => [
                'total'        => $libros->total(),
                'current_page' => $libros->currentPage(),
                'per_page'     => $libros->perPage(),
                'last_page'    => $libros->lastPage(),
                'from'         => $libros->firstItem(),
                'to'           => $libros->lastItem(),
            ],
            'libros' => $libros
        ];
    }

    public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            $libro=new Libro();
            $libro->nombre = $request->nombre;
            $libro->genero = $request->genero;
            $libro->grado = $request->grado;
            $libro->descripcion = $request->descripcion;
            $libro->stock = $request->stock;
            $libro->precio = $request->precio;
            $libro->estado = '1';
            $libro->save();
        }
        public function desactivar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $libro = Libro::findOrFail($request->id);
            $libro->nombre = $request->nombre;
            $libro->genero = $request->genero;
            $libro->grado = $request->grado;
            $libro->descripcion = $request->descripcion;
            $libro->stock = $request->stock;
            $libro->precio = $request->precio;
            $libro->estado = '0';
            $libro->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $libro = Libro::findOrFail($request->id);
            $libro->estado = '1';
            $libro->save();
        }
    }

