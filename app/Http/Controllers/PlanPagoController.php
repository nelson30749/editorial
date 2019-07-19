<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\PlanPago;
use App\Cuota;

class PlanPagoController extends Controller
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio=$request->criterio;
            $plan_pagos= PlanPago::join('entregas','plan_pagos.idEntrega','=','entregas.id')
            ->join('promotores','entregas.idPromotor','=','promotores.id')
            ->select('plan_pagos.id','idEntrega',DB::raw("concat(promotores.nombre,' ',promotores.apellido) as promotor"),'plan_pagos.fecha','fechaInicio','fechaFin','plan_pagos.montoTotal','plan_pagos.pago','plan_pagos.estado')
            ->where('promotores.'.$criterio,'like','%'.$buscar.'%')
            ->where('plan_pagos.estado','=','1')
            ->orderBy('plan_pagos.id','desc')->paginate(10);
        return [
            'pagination' => [
                'total'        => $plan_pagos->total(),
                'current_page' => $plan_pagos->currentPage(),
                'per_page'     => $plan_pagos->perPage(),
                'last_page'    => $plan_pagos->lastPage(),
                'from'         => $plan_pagos->firstItem(),
                'to'           => $plan_pagos->lastItem(),
            ],
            'plan_pagos' => $plan_pagos
        ];
    }
    public function listarDetalle(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
            $id=$request->id;
            $detalles= Cuota::where('idPlan','=',$id)
            ->where('cuotas.estado','=','1')
            ->get();
     
        return ['detalles' => $detalles];
    }
    public function store(Request $request)
    {
        $mytime= Carbon::now('America/La_Paz');
        $cuota=new Cuota();
        $cuota->idPlan=$request->idPlan;
        $cuota->nro=1;
        $cuota->fecha=$mytime->toDateTimeString();
        $cuota->monto=$request->monto;
        $cuota->estado='1';
        $cuota->save();
    }
}
