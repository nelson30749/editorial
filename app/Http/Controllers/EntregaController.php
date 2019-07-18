<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Entrega;
use App\PlanPago;
use App\DetalleEntrega;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        if($buscar=='')
        {
            $entregas= Entrega::join('promotores','entregas.idPromotor','=','promotores.id')
            ->select('entregas.id','nro',DB::raw("concat(promotores.nombre,' ',promotores.apellido) promotor"),'idPromotor','fecha','comprobante','cantidad','montoTotal','entregas.estado')
            ->where('entregas.estado','=','1')
            ->orderBy('entregas.estado','desc')->orderBy('entregas.id','desc')->paginate(10);
        }
        else{
            $entregas= Entrega::join('promotores','entregas.idPromotor','=','promotores.id')
            ->select('entregas.id','nro',DB::raw("concat(promotores.nombre,' ',promotores.apellido) promotor"),'idPromotor','fecha','comprobante','cantidad','montoTotal','entregas.estado')
            ->where('promotores.nombre','like','%'.$buscar.'%')
            ->where('promotores.apellido','like','%'.$buscar.'%')
            ->where('entregas.estado','=','1')
            ->orderBy('entregas.id','desc')->paginate(10);
            
        }
     
        return [
            'pagination' => [
                'total'        => $entregas->total(),
                'current_page' => $entregas->currentPage(),
                'per_page'     => $entregas->perPage(),
                'last_page'    => $entregas->lastPage(),
                'from'         => $entregas->firstItem(),
                'to'           => $entregas->lastItem(),
            ],
            'entregas' => $entregas
        ];
    }

    public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            DB::beginTransaction();
            try{
                
                $mytime= Carbon::now('America/La_Paz');
                $entrega = new Ingreso();
                $entrega->nro = $request->nro;
                $entrega->idPromotor=$request->idPromotor;
                $entrega->fecha = $mytime->toDateString();                
                $entrega->comprobante=$request->comprobante;
                $entrega->cantidad = $request->cantidad;
                $entrega->montoTotal = $request->montoTotal;
                $entrega->estado = '1';
                $entrega->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               
                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleEntrega();
                    $detalle->idEntrega= $ingreso->idEntrega;
                    $detalle->idLibro = $det['idLibro'];
                    $detalle->cantidad = $det['cantidad'];   
                    $detalle->precio = $det['precio']; 
                    $detalle->estado= '1';
                    $detalle->save();
                } 

                $plan_pago=new PlanPago();
                $plan_pago->idEntrega=$entrega->id;
                $plan_pago->fecha= $mytime->toDateString();
                $plan_pago->fechaInicio=$request->fechaInicio;
                $plan_pago->fechaFin= $request->fechaFin;
                $plan_pago->pago=$request->pago;
                $plan_pago->montoTotal=$request->montoTotal;
                $plan_pago->estado='1';
                $plan_pago->save();

                DB::commit();
            } catch (Exception $e){
                DB::rollBack();
            }
        }
        public function desactivar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $entrega = Entrega::findOrFail($request->id);
            $entrega->estado = '0';
            $entrega->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $entrega = Entrega::findOrFail($request->id);
            $entrega->estado = '1';
            $entrega->save();
        }

}