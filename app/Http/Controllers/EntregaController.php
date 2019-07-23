<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Entrega;
use App\PlanPago;
use App\Cuota;
use App\Libro;
use App\DetalleEntrega;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio=$request->criterio;
            $entregas= Entrega::join('promotores','entregas.idPromotor','=','promotores.id')
            ->select('entregas.id','nro',DB::raw("concat(promotores.nombre,' ',promotores.apellido) as promotor"),'idPromotor','fecha','comprobante','cantidad','montoTotal','pago','entregas.estado')
            ->where('entregas.estado','=','1')
            ->where('promotores.'.$criterio,'like','%'.$buscar.'%')
            ->orderBy('entregas.id','desc')->paginate(10);
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
    public function listarDetalle(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
            $id=$request->id;
            $detalles= DetalleEntrega::join('libros','detalle_entregas.idLibro','=','libros.id')
            ->select('libros.id','libros.nombre','libros.genero','libros.grado','libros.descripcion','detalle_entregas.precio','detalle_entregas.cantidad')
            ->where('detalle_entregas.idEntrega','=',$id)
            ->where('detalle_entregas.estado','=','1')
            ->get();
     
        return ['detalles' => $detalles];
    }
    public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            DB::beginTransaction();
            try{
                
                $mytime= Carbon::now('America/La_Paz');
                $entrega = new Entrega();
                $year=date('Y');
                $contar=Entrega::whereYear('entregas.fecha','=',$year)->count();
                $entrega->nro = $contar+1;
                $entrega->idPromotor=$request->idPromotor;
                $entrega->fecha = $mytime->toDateTimeString();                 
                $entrega->comprobante=$request->comprobante;
                $entrega->cantidad = $request->cantidad;
                $entrega->montoTotal = $request->montoTotal;
                $entrega->pago = $request->pago;
                $entrega->estado = '1';
                $entrega->save();

                // PLAN PAGO
                if($entrega->pago==0){
                $plan_pago=new PlanPago();
                $plan_pago->idEntrega=$entrega->id;
                $plan_pago->fecha= $mytime->toDateTimeString();  
                $plan_pago->fechaInicio=$request->fechaInicio;
                $plan_pago->fechaFin= $request->fechaFin;
                $plan_pago->pago=$request->pago;
                $plan_pago->montoTotal=$request->montoTotal;
                $plan_pago->estado='1';
                $plan_pago->save();

                $sw=$request->cuota;
                if(!$sw)
                {
                    $cuotas=new Cuota();
                    $cuotas->idPlan=$plan_pago->id;
                    $contar=Cuota::whereYear('fecha','=',$year)->max('nro');
                    $cuotas->nro = $contar+1;
                    $cuotas->nro=1;
                    $cuotas->fecha=$mytime->toDateTimeString();
                    $cuotas->monto=$request->montoCuota;
                    $cuotas->estado='1';
                    $cuotas->save();
                }
                }

                //coutas
               
                
                //DETALLES
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               
                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleEntrega();
                    $detalle->idEntrega= $entrega->id;
                    $detalle->idLibro = $det['id'];
                    $detalle->cantidad = $det['cantidad'];   
                    $detalle->precio = $det['precio']; 
                    $detalle->estado= '1';
                    $detalle->save();
                    
                    $libro = Libro::find($det['id']);
                    $libro->stock=$libro->stock-$detalle->cantidad;
                    $libro->save();
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
                
                $mytime= Carbon::now('America/La_Paz');
                $entrega = Entrega::findOrFail($request->id);
                $entrega->nro = 1;
                $entrega->idPromotor=$request->idPromotor;
                $entrega->fecha = $mytime->toDateString();                
                $entrega->comprobante=$request->comprobante;
                $entrega->cantidad = $request->cantidad;
                $entrega->montoTotal = $request->montoTotal;
                $entrega->pago = $request->pago;
                $entrega->estado = '1';
                $entrega->save();

                //PLAN PAGO
                // $plan_pago=new PlanPago();
                // $plan_pago->idEntrega=$entrega->id;
                // $plan_pago->fecha= $mytime->toDateString();
                // $plan_pago->fechaInicio=$request->fechaInicio;
                // $plan_pago->fechaFin= $request->fechaFin;
                // $plan_pago->pago=$request->pago;
                // $plan_pago->montoTotal=$request->montoTotal;
                // $plan_pago->estado='1';
                // $plan_pago->save();

                //DETALLES
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
                $detalle = DetalleEntrega::where('idEntrega','=',$entrega->id)->update(['estado'=>'0']);
                foreach($detalles as $ep=>$det)
                {
                    $detalle_entrega=DetalleEntrega::updateOrInsert(['idEntrega' =>$entrega->id,'idLibro'=>$det['id']],
                    ['cantidad'=>$det['cantidad'],'precio'=>$det['precio'],'estado'=>'1']);
                }
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