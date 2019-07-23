<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ingreso;
use App\Entrega;
// use DB;

class DashboardController extends Controller
{
   public function __invoke(Request $request)
   {
    // if(!$request->ajax()) return redirect('/');
        $year=date('Y');
        $ingresos=Ingreso::select(DB::raw('MONTHNAME(ingresos.fecha) as mes'),
        DB::raw('YEAR(ingresos.fecha) as year'),
        DB::raw('SUM(ingresos.montoTotal) as total'))
        ->where('ingresos.estado','=','1')
        ->whereYear('ingresos.fecha',$year)
        ->groupBy(DB::raw('MONTHNAME(ingresos.fecha)'),DB::raw('YEAR(ingresos.fecha)'))
        ->get();
// tipo=0 es egreso
        $entregas=Entrega::select(DB::raw('MONTHNAME(entregas.fecha) as mes'),
        DB::raw('YEAR(entregas.fecha) as year'),
        DB::raw('SUM(entregas.montoTotal) as total'))
        ->whereYear('entregas.fecha',$year)
        ->where('entregas.estado','=','1')
        ->groupBy(DB::raw('MONTHNAME(entregas.fecha)'),DB::raw('YEAR(entregas.fecha)'))
        ->get();

        return ['ingresos'=>$ingresos,'entregas'=>$entregas,'year'=>$year];
    }
}