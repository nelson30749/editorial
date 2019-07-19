@extends('principal')
@section('contenido')

       <template v-if="menu==0"> 
              <dashboard></dashboard>
       </template>

       <template v-if="menu==1">   
       <libro></libro>
       </template> 

       <template v-if="menu==2">
       <ingreso></ingreso>        
       </template>

       <template v-if="menu==3">
       <proveedor></proveedor>      
       </template>

       <template v-if="menu==4">
       <entrega></entrega>
       </template>

       <template v-if="menu==5">
       <promotor></promotor> 
       </template>

       <template v-if="menu==6">
       <provincia></provincia>
       </template>

       <template v-if="menu==7">
       <departamento></departamento>
       </template>

       <template v-if="menu==8">
              <plan_pago></plan_pago>
       </template>

       <template v-if="menu==9">
              <h1>Contenido del menu 9</h1>
       </template>

       <template v-if="menu==10">
              <h1>Contenido del menu 10</h1>
       </template>

       <template v-if="menu==11">
              <h1>Contenido del menu 11</h1>
       </template>

       <template v-if="menu==12">
              <h1>Contenido del menu 12</h1>
       </template> 

@endsection