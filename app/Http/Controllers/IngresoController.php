<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Ingreso;
use App\DetalleIngreso;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        if($buscar=='')
        {
            $ingresos= Ingreso::join('proveedores','ingresos.idProveedor','=','proveedores.id')
            ->select('ingresos.id','nro','proveedores.nombre as proveedor','idProveedor','fecha','cantidad','montoTotal','ingresos.estado')
            ->where('ingresos.estado','=','1')
            ->orderBy('ingresos.estado','desc')->orderBy('ingresos.id','desc')->paginate(10);
        }
        else{
            $ingresos= Ingreso::join('proveedores','ingresos.idProveedor','=','proveedores.id')
            ->select('ingresos.id','nro','proveedores.nombre as proveedor','idProveedor','fecha','cantidad','montoTotal','ingresos.estado')
            ->where('proveedores.nombre','like','%'.$buscar.'%')
            ->where('ingresos.estado','=','1')
            ->orderBy('ingresos.id','desc')->paginate(10);
            
        }
     
        return [
            'pagination' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }

    public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            DB::beginTransaction();
            try{
                $mytime= Carbon::now('America/La_Paz');
                $ingreso = new Ingreso();
                $ingreso->nro = $request->nro;
                $ingreso->idProveedor=$request->idProveedor;
                $ingreso->fecha = $mytime->toDateTimeString();                
                $ingreso->cantidad = $request->cantidad;
                $ingreso->montoTotal = $request->montoTotal;
                $ingreso->estado = '1';
                $ingreso->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               
                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleIngreso();
                    $detalle->idIngreso= $ingreso->idIngreso;
                    $detalle->idLibro = $det['idLibro'];
                    $detalle->cantidad = $det['cantidad'];   
                    $detalle->precio = $det['precio']; 
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
            $ingreso = Ingreso::findOrFail($request->id);
            $ingreso->estado = '0';
            $ingreso->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $ingreso = Ingreso::findOrFail($request->id);
            $ingreso->estado = '1';
            $ingreso->save();
        }

}
