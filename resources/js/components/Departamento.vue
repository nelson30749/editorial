<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>  
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Departamentos
                <button type="button" @click="abrirModal('departamento','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre">Nombre</option>
                              <!-- <option value="descripcion">Descripción</option> -->
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listar(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listar(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-responsive-sm table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>               
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </tr>
                    </thead>
                    <tbody>
                        <tr v-for="data in arrayData" :key="data.id">
                            
                            <td>{{ data.id }}</td>
                            <td v-text="data.nombre"></td>
                            <td>
                                <div v-if="data.estado">
                                   <span class="badge badge-success">Activo</span>
                                </div>
                                 <div v-else>
                                   <span class="badge badge-danger">Desactivado</span>
                                </div>

                            </td>
                            <td>
                                <button type="button" @click="abrirModal('departamento','actualizar',data)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;

                                <template v-if="data.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivar(data.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activar(data.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                            </td>
                        </tr>
                       
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                        
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del Departamento">
                                
                            </div>
                        </div>                           
                     <div v-show="error" class="form-group row div-error">
                           <div class="text-center text-error">
                                 <div v-for="errors in errorMostrarMsj" :key="errors" v-text="errors">
                                 </div>
                           </div>
                    </div>
                    <!-- </form> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
    <!-- Inicio del modal Eliminar -->

    <!-- Fin del modal Eliminar -->
</main>
</template>

<script>
export default {
  data() {
    return {
      departamento_id: 0,
      nombre: '',
      arrayData: [],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      error: 0,
      errorMostrarMsj: [],
      pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
    }
  },
  computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
  },

  methods: {
    listar(page,buscar,criterio) {
      let me = this;
      var url= '/departamento?page=' + page + '&buscar='+ buscar;
      axios.get(url).then(function(response) {
          // handle success
         var respuesta= response.data;
         me.arrayData = respuesta.departamentos.data;
         me.pagination= respuesta.pagination;
          //console.log(response);
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
    },
     cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listar(page,buscar,criterio);
            },
    registrar() {
      if (this.validar()) {
        return;
      }
      let me = this;

      axios
        .post("/departamento/registrar", {
          'nombre': this.nombre,
          // 'descripcion': this.descripcion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listar(1,'','nombre');
          swal({
            position: 'center',
            type: 'success',
            title: 'Se Registro Correctamente',
            showConfirmButton: false,
            timer: 1500
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    actualizar() {
      if (this.validar()) {
        return;
      }
      let me = this;
      axios
        .put("/departamento/actualizar", {
          'nombre': this.nombre,
          // 'descripcion': this.descripcion,
          'id': this.departamento_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listar(1,'','nombre');
          swal({
            position: 'center',
            type: 'success',
            title: 'Se Actualizo Correctamente',
            showConfirmButton: false,
            timer: 1500
          });
        })
        .catch(function(error) {
          console.log(error);
        });       
    },
    desactivar(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Esta seguro de desactivar?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          axios
            .put("/departamento/desactivar", {
              id: id
            })
            .then(function(response) {
              me.listar(1,'','nombre');
              swalWithBootstrapButtons(
                "Desactivado!",
                "El registro ha sido desactivado con exito.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
          /* swalWithBootstrapButtons(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
    )*/
        }
      });
    },

    activar(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Esta seguro de activar?",
       // text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          axios
            .put("/departamento/activar", {
              id: id
            })
            .then(function(response) {
              me.listar(1,'','nombre');
              swalWithBootstrapButtons(
                "Activado!",
                "El registro ha sido activado con exito.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
          /* swalWithBootstrapButtons(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
    )*/
        }
      });
    },
    validar() {
      this.error = 0;
      this.errorMostrarMsj = [];

      if (!this.nombre)
        this.errorMostrarMsj.push(
          "El nombre del departamento no puede estar vacio."
        );

      if (this.errorMostrarMsj.length) this.error= 1;

      return this.error;
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "departamento": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Departamento";
              this.nombre = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              // console.log(data);
              this.modal = 1;
              this.tituloModal = "Actualizar";
              this.tipoAccion = 2;
              this.departamento_id = data["id"];
              this.nombre = data["nombre"];
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listar(1,this.buscar,this.criterio);
    //console.log('Component mounted.')
  }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
</style>