<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i>Proveedores
                        <button type="button" @click="abrirModal('proveedor','registrar')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listar(1,buscar,criterio)" class="form-control" placeholder="Buscar" >
                                    <button type="submit" @click="listar(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                 
                        <table class="table table-responsive-sm table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Telefono</th>
                                    <th>Direccion</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="data in arrayData" :key="data.id">
                                    <td><span class="badge badge-success" v-text="data.id"></span></td>
                                    <td v-text="data.nombre"></td>
                                    <td v-text="data.telefono"></td>
                                    <td v-text="data.direccion"></td>
                                    <td>
                                        <div v-if="data.estado">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" @click="abrirModal('proveedor','actualizar',data)" class="btn btn-warning btn-sm" >
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
            <div class="modal fade" tabindex="-1" :class="{ 'mostrar' : modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre.............">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Telefono..............">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Direccion............">
                                    </div>
                                </div>
                                <div v-show="error" class="form-group row">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsj" :key="error" v-text="error">

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
        </main>
</template>

<script>
    export default {
        data()
        {
            return {
                proveedor_id: 0,
                nombre :'',
                telefono : '',
                direccion : '',
                arrayData : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                error: 0,
                errorMostrarMsj : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio:'nombre',
                buscar : ''
            }
        },
        computed :
        {
            isActived: function()
            {
                return this.pagination.current_page;
            },
            pagesNumber: function()
            {
                if(!this.pagination.to)
                {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if(from < 1)
                {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page)
                {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to)
                {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods :
        {
            listar(page,buscar,criterio)
            {
                let me=this;
                var url= '/proveedor?page=' + page + '&buscar=' + buscar +'&criterio='+criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayData = respuesta.proveedores.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio)
            {
                let me= this;
                // actualizar la Pagina
                me.pagination.current_page = page;
                // enviar la peticion para visualizar la data de esta pagina
                me.listar(page,buscar,criterio);
            },
            registrar()
            {
                if(this.validar())
                {
                    return;
                }
                let me=this;

                axios.post('/proveedor/registrar',{
                    nombre : this.nombre,
                    telefono : this.telefono,
                    direccion : this.direccion
                }).then(function (response)
                {
                    me.cerrarModal();
                    me.listar(1,'','nombre');
                }).catch(function (error)
                {
                    console.log(error);
                });
            },
            actualizar()
            {
                 if(this.validar())
                {
                    return;
                }
                let me=this;

                axios.put('/proveedor/actualizar',{
                    nombre : this.nombre,
                    telefono : this.telefono,
                    direccion : this.direccion,
                    id : this.proveedor_id
                }).then(function (response)
                {
                    me.cerrarModal();
                    me.listar(1,'','nombre');
                }).catch(function (error)
                {
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
              .put("/proveedor/desactivar", {
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
              .put("/proveedor/activar", {
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
            validar()
            {
                this.error=0;
                this.errorMostrarMsj = [];

                if (!this.nombre) this.errorMostrarMsj.push("Ingrese el Nombre del Proveedor");
                if (this.errorMostrarMsj.length) this.error = 1;
                return this.error;
            },
            cerrarModal()
            {
                this.modal= 0,
                this.tituloModal= '';
                this.nombre='';
                this.telefono='';
                this.direccion='';
            },
            abrirModal(modelo, accion, data = [])
            {
                switch(modelo)
                {

                    case 'proveedor':
                    {
                        switch(accion)
                        {
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Proveedor';
                                this.nombre='';
                                this.telefono='';
                                this.direccion='';
                                this.tipoAccion = 1;
                                break;

                            }
                            case 'actualizar':
                            {
                               // console.log(data);
                               this.modal=1;
                               this.tituloModal='Actualizar Proveedor';
                               this.tipoAccion=2;
                               this.proveedor_id =data['id'];
                               this.nombre=data['nombre'];
                                this.telefono=data['telefono'];
                                this.direccion= data['direccion'];
                               break;
                            }
                        }
                    }
                    
                }
            }
        },
        mounted() {
            this.listar(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }   
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>

