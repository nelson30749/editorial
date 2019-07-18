@extends('principal')
@section('contenido')

       <template v-if="menu==0"> 
      <h1>Contenido del menu 0</h1>
       </template>

       <template v-if="menu==1">   
       <equipo></equipo>
       </template> 

       <template v-if="menu==2">
       <categoria></categoria>
        
       </template>

       <template v-if="menu==3">
                     <horario></horario> 
     
       
       </template>

       <template v-if="menu==4">
                     <empleado></empleado>      
       <!-- <pago></pago>  -->
       </template>

       <template v-if="menu==5">
                     <reserva></reserva>
                         
       
       </template>

       <template v-if="menu==6">
                     <cliente></cliente>
       
       </template>

       <template v-if="menu==7">
                     <h1>Contenido del menu 7</h1>
       </template>

       <template v-if="menu==8">
              <h1>Contenido del menu 8</h1>
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