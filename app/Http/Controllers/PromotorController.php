<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Promotor;
use App\DetallePromotor;

class PromotorController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        if($buscar=='')
        {
            $promotores= Promotor::where('promotores.estado','=','1')
            ->orderBy('id','desc')
            ->paginate(10);
        }
        else{
            $promotores= Promotor::where('promotores.nombre','like'.'%'.$buscar.'%')
            ->where('promotores.apellido','like'.'%'.$buscar.'%')
            ->where('promotores.estado','=','1')
            ->orderBy('promotores.id','desc')->paginate(10);
            
        }
     
        return [
            'pagination' => [
                'total'        => $promotores->total(),
                'current_page' => $promotores->currentPage(),
                'per_page'     => $promotores->perPage(),
                'last_page'    => $promotores->lastPage(),
                'from'         => $promotores->firstItem(),
                'to'           => $promotores->lastItem(),
            ],
            'promotores' => $promotores
        ];
    }

    public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            DB::beginTransaction();
            try{
                $promotor->nombre = $request->nombre;
                $promotor->apellido=$request->apellido;             
                $promotor->ci=$request->ci;
                $promotor->direccion = $request->direccion;
                $promotor->telefono = $request->telefono;
                $promotor->email = $request->email;
                $promotor->estado = '1';
                $promotor->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               
                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetallePromotor();
                    $detalle->idEntrega= $promotor->idPromotor;
                    $detalle->idProvincia = $det['idProvincia'];
                    $detalle->estado= '1';
                    $detalle->save();
                } 
                DB::commit();
            } catch (Exception $e){
                DB::rollBack();
            }
        }
        public function desactivar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $promotor = Promotor::findOrFail($request->id);
            $promotor->estado = '0';
            $promotor->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $promotor = Promotor::findOrFail($request->id);
            $promotor->estado = '1';
            $promotor->save();
        }

}