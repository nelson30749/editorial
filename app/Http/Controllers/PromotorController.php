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
        $criterio = $request->criterio;
        
            $promotores= Promotor::where('promotores.'.$criterio,'like','%'.$buscar.'%')
            ->where('promotores.estado','=','1')
            ->orderBy('promotores.id','desc')->paginate(10);
            
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
    public function listarDetalle(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
            $id=$request->id;
            $detalles= DetallePromotor::join('provincias','detalle_promotores.idProvincia','=','provincias.id')
            ->join('departamentos','provincias.idDpto','departamentos.id')
            ->select('provincias.id','provincias.nombre as provincia','departamentos.nombre as departamento')
            ->where('detalle_promotores.idPromotor','=',$id)
            ->where('detalle_promotores.estado','=','1')
            ->get();
     
        return ['detalles' => $detalles];
    }
    public function select(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $promotores= Promotor::select('id',DB::raw("concat(promotores.nombre,' ',promotores.apellido) as promotor"))
        ->where('promotores.nombre','like','%'.$buscar.'%')
        ->where('promotores.estado','=','1')
        ->orderBy('promotores.id','desc')
        ->limit(10)
        ->get();
        
        return ['promotores' => $promotores];
    }
    public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            DB::beginTransaction();
            try{
                $promotor=new Promotor();
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
                    $detalle->idPromotor= $promotor->id;
                    $detalle->idProvincia = $det['id'];
                    $detalle->estado= '1';
                    $detalle->save();
                } 
                DB::commit();
            } catch (Exception $e){
                DB::rollBack();
            }
        }
        public function update(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            DB::beginTransaction();
            try{
                $promotor=Promotor::findOrFail($request->id);
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
                $detalle = DetallePromotor::where('idPromotor','=',$promotor->id)->update(['estado'=>'0']);
                foreach($detalles as $ep=>$det)
                {
                    $detalle_promotor=DetallePromotor::updateOrInsert(['idPromotor' =>$promotor->id,'idProvincia'=>$det['id']],['estado'=>'1']);
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