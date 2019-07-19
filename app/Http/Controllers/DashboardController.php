<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function __invoke(Request $request)
   {
    // if(!$request->ajax()) return redirect('/');
     $year=date('Y');
        $ingresos=DB::table('ingresos')
        ->select(DB::raw('MONTH(ingresos.fecha) as mes'),
        DB::raw('YEAR(ingresos.fecha) as year'),
        DB::raw('SUM(ingresos.montoTotal) as total'))
        ->where('ingresos.estado','=','1')
        ->whereYear('ingresos.fecha',$year)
        ->groupBy(DB::raw('MONTH(ingresos.fecha)'),DB::raw('YEAR(ingresos.fecha)'))
        ->get();
// tipo=0 es egreso
        $entregas=DB::table('entregas')
        ->select(DB::raw('MONTH(entregas.fecha) as mes'),
        DB::raw('YEAR(entregas.fecha) as year'),
        DB::raw('SUM(entregas.montoTotal) as total'))
        ->whereYear('entregas.fecha',$year)
        ->where('entregas.estado','=','1')
        ->groupBy(DB::raw('MONTH(entregas.fecha)'),DB::raw('YEAR(entregas.fecha)'))
        ->get();
        return ['ingresos'=>$ingresos,'entregas'=>$entregas,'year'=>$year];      
 
    }
}