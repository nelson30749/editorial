<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PlanPago;
use App\Cuota;

class ReporteController extends Controller
{
    public function promotor(Request $request)
    {
        $buscar = $request->buscar;
        $criterio=$request->criterio;
        $plan_pagos= PlanPago::join('cuotas','plan_pagos.id','cuotas.idPlan')
        ->join('entregas','plan_pagos.idEntrega','=','entregas.id')
        ->join('promotores','entregas.idPromotor','=','promotores.id')
        ->select('promotores.id',DB::raw("concat(promotores.nombre,' ',promotores.apellido) as promotor"),DB::raw('sum(cuotas.monto)as pagado'))
        ->where('plan_pagos.estado','=','1')
        ->where('promotores.'.$criterio,'like','%'.$buscar.'%')
        ->groupBy('promotores.id','promotores.nombre','promotores.apellido')
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
}
