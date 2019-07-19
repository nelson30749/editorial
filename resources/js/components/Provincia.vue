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
                <i class="fa fa-align-justify"></i> Provincia
                <button type="button" @click="abrirModal('provincia','registrar')" class="btn btn-secondary">
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
                            <th>Provincia</th>
                            <th>Departamento</th>
                            <th>Estado</th>
                            <th>Opciones</th>  
                                                     
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="data in arrayData" :key="data.id">
                            <td><span class="badge badge-success" v-text="data.id"></span></td>
                            <td v-text="data.provincia"></td>
                            <td v-text="data.departamento"></td>
                            <td>
                                <div v-if="data.estado">
                                   <span class="badge badge-success">Activo</span>
                                </div>
                                 <div v-else>
                                   <span class="badge badge-danger">Desactivado</span>
                                </div>

                            </td>
                            <td>
                                <button type="button" @click="abrirModal('provincia','actualizar',data)" class="btn btn-warning btn-sm">
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
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <v-select
                                v-model="selectedDpto"
                                @search="selectDpto"
                                label="nombre"
                                :options="arrayDpto"
                                placeholder="Buscar Departartamento..."
                                @input="getDatosDpto"
                              ></v-select>
                            </div>
                        </div>
                           
                     <div v-show="error" class="form-group row div-error">
                           <div class="text-center text-error">
                                 <div v-for="errors in errorMostrarMsj" :key="errors" v-text="errors">
                                 </div>
                           </div>
                    </div>
                   

                    </form>
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
      provincia_id: 0,
      idDpto :0,
      nombre: '',
      departamento : '',
      arrayData: [],
      arrayDpto:[],
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
                buscar : '',
                selectedDpto:null
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
      var url= '/provincia?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
      axios.get(url).then(function(response) {
          // handle success
         var respuesta= response.data;
         me.arrayData = respuesta.provincias.data;
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
             selectDpto(search, loading) {
      let me = this;
      loading(true);
      var url = "/departamento/select?buscar=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayDpto = respuesta.departamentos;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosDpto(val1) {
      let me = this;
      me.loading = true;
      me.idDpto = val1.id;
      me.departamento = val1.nombre;
    },
    registrar() {
      if (this.validar()) {
        return;
      }
      let me = this;

      axios
        .post("/provincia/registrar", {
          nombre: this.nombre,
          idDpto: this.idDpto
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
        .put("/provincia/actualizar", {
           nombre: this.nombre,
          idDpto: this.idDpto,
          id: this.provincia_id
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
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "Estas Seguro de Desactivar el Registro?",
          text: "Si Desactiva no estara en la Lista!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, Desactivar!",
          cancelButtonText: "No, Cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;

            axios
              .put("/provincia/desactivar", {
                id: id
              })
              .then(function(response) {
                me.listar(1, "", "nombre");
                Swal.fire({
                  position: "center",
                  type: "success",
                  title: "El Registro ha sido Desactivado",
                  showConfirmButton: false,
                  timer: 1000
                }).catch(function(error) {
                  console.log(error);
                });
              });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
              position: "center",
              type: "error",
              title: "Cancelado",
              showConfirmButton: false,
              timer: 1000
            });
          }
        });
    },
    activar(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "Estas Seguro de Activar el Registro?",
          text: "Si Activa Estara en la Lista!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, Activar!",
          cancelButtonText: "No, Cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;

            axios
              .put("/provincia/activar", {
                id: id
              })
              .then(function(response) {
                me.listar(1, "", "nombre");
                Swal.fire({
                  position: "center",
                  type: "success",
                  title: "El Registro ha sido Activado",
                  showConfirmButton: false,
                  timer: 1000
                }).catch(function(error) {
                  console.log(error);
                });
              });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
              position: "center",
              type: "error",
              title: "Cancelado",
              showConfirmButton: false,
              timer: 1000
            });
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
     this.limpiarRegistro();
    },
    limpiarRegistro()
    { 
      this.provincia= "";
      this.nombre="";
      this.selectedDpto=null;
      this.idProvincia= 0;
      this.idDpto=0;
      this.departamento="";
      this.arrayDetalle = [];
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "provincia": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Provincia";
              this.limpiarRegistro();
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.provincia_id = data["id"];
              this.nombre = data["provincia"];
              this.departamento=data["departamento"]
              this.idDpto=data["idDpto"];
              this.selectedDpto={id:data["idDpto"],nombre:data["departamento"]};
              this.modal = 1;
              this.tituloModal = "Actualizar categoria";
              this.tipoAccion = 2;
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