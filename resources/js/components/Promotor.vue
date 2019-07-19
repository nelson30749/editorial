<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Promotores
          <button
            type="button"
            @click="mostrarDetalle('promotor','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <!---Listado -->
        <template v-if="listado==1">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-5" v-model="criterio">
                    <option value="nombre">Nombre</option>                    
                    <option value="apellido">Apellido</option>
                    <option value="ci">C.I.</option>
                    <option value="direccion">Direccion</option>
                    <option value="telefono">Telefono</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listar(1,buscar,criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button type="submit" @click="listar(1,buscar,criterio)" class="btn btn-primary">
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </div>

            <table class="table table-responsive-sm table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre Apellido</th>
                  <th>CI</th>
                  <th>Telefono</th>
                  <th>Direccion</th>
                  <th>Correo</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in arrayData" :key="data.id">
                  <td>
                    <span class="badge badge-success" v-text="data.id"></span>
                  </td>
                  <td>{{ data.nombre+' '+data.apellido }}</td>
                  <td v-text="data.ci"></td>
                  <td v-text="data.telefono"></td>
                  <td v-text="data.direccion"></td>
                  <td v-text="data.email"></td>
                  <td>
                    <div v-if="data.estado">
                      <span class="badge badge-success">Activo</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-danger">Desactivado</span>
                    </div>
                  </td>
                  <td>
                    <button
                      type="button"
                      @click="mostrarDetalle('promotor','actualizar',data)"
                      class="btn btn-warning btn-sm"
                    >
                      <i class="icon-pencil"></i>
                    </button> &nbsp;
                    <template v-if="data.estado">
                      <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="desactivar(data.id)"
                      >
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
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
                  >Ant</a>
                </li>
                <li
                  class="page-item"
                  v-for="page in pagesNumber"
                  :key="page"
                  :class="[page == isActived ? 'active' : '']"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(page,buscar,criterio)"
                    v-text="page"
                  ></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                  >Sig</a>
                </li>
              </ul>
            </nav>
          </div>
        </template>
        <!-- Fin de Listado-->
        <!---Detalle-->

        <template v-else-if="listado==0">
          <div class="card-body">
            <div class="form-group row border">
              <div class="col-md-6">
                <div class="form-group">
                  <label for>
                    Nombre
                    <br>
                  </label>
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Nombre"
                  >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for>
                    Apellido
                    <br>
                  </label>
                  <input
                    type="text"
                    v-model="apellido"
                    class="form-control"
                    placeholder="Apellido"
                  >
                </div>
              </div>
            <div class="col-md-4">
                <div class="form-group">
                  <label for>
                    Correo
                    <br>
                  </label>
                  <input
                    type="text"
                    v-model="email"
                    class="form-control"
                    placeholder="example@email.com"
                  >
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for>
                    Telefono
                    <br>
                  </label>
                  <input
                    type="text"
                    v-model="telefono"
                    class="form-control"
                    placeholder="Telefono"
                  >
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for>
                    CI
                    <br>
                  </label>
                  <input
                    type="text"
                    v-model="ci"
                    class="form-control"
                    placeholder="Cedula de Identidad"
                  >
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for>
                    Direccion
                    <br>
                  </label>
                  <input
                    type="text"
                    v-model="direccion"
                    class="form-control"
                    placeholder="Direccion"
                  >
                </div>
              </div>
              <div class="col-md-12">
                <div v-show="errorMostrar" class="form-group row div-error">
                  <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsj" :key="error" v-text="error"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row border">
              <div class="col-md-8">
                <div class="form-group">
                  <label>
                    Provincia
                    <span v-show="provincia==''">(*Selecione)</span>
                  </label>
                  <v-select
                    @search="selectProvincia"
                    label="provincia"
                    :options="arrayProvincia"
                    placeholder="Buscar Provincia..."
                    @input="getDatosProvincia"
                  ></v-select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <button @click="abrirModal()" class="btn btn-success form-control btnagregar">
                    <i class="icon-plus"></i>
                    <i class="icon-plus"></i>
                    <i class="icon-plus"></i>
                  </button>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar">
                    <i class="icon-plus">Agregar</i>
                  </button>
                </div>
              </div>
            </div>
            <div class="form-group row border">
              <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Opciones</th>
                      <td>ID</td>
                      <th>Departamento</th>
                      <th>Provincia</th>
                    </tr>
                  </thead>
                  <tbody v-if="(arrayDetalle.length)">
                    <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                      <td>
                        <button
                          @click="eliminarDetalle(index)"
                          type="button"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td>
                        <span class="badge badge-success" v-text="detalle.id"></span>
                      </td>

                      <td v-text="detalle.departamento"></td>
                      <td>{{ detalle.provincia }}</td>
                      
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="4">No hay Productos Agregados</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!---listado formula-->
            <div class="form-group row">
              <div class="col-md-12">
                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                <button
                  type="button"
                  v-if="tipoAccion==1"
                  class="btn btn-primary"
                  @click="registrar()"
                >Guardar</button>
                <button
                  type="button"
                  v-if="tipoAccion==2"
                  class="btn btn-primary"
                  @click="actualizar();"
                >Actualizar</button>
              </div>
            </div>
          </div>
        </template>
        <!--Fin de Detalle -->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar' : modal}"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterioP">
                    <option value="provincia">Provincia</option>
                    <option value="departamento">Departamento</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscarP"
                    @keyup="listarProvincia(buscarP,criterioP)"
                    class="form-control"
                    placeholder="Buscar Producto"
                  />
                  <button
                    type="submit"
                    @click="listarProvincia(buscarP,criterioP)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Departamento</th>
                    <th>Provincia</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="provincias in arrayProvincia" :key="provincias.id">
                    <td>
                      <button
                        type="button"
                        @click="agregarDetalleModal(provincias)"
                        class="btn btn-success btn-sm"
                      >
                        <i class="icon-check"></i>
                      </button>
                    </td>
                    <td v-text="provincias.departamento"></td>
                    <td v-text="provincias.provincia"></td>
                    <td>
                      <div v-if="provincias.estado">
                        <span class="badge badge-success">Activo</span>
                      </div>
                      <div v-else>
                        <span class="badge badge-danger">Desactivado</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
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
import Vue from "vue";
import vSelect from "vue-select";

import "vue-select/dist/vue-select.css";

Vue.component("v-select", vSelect);
export default {
  data() {
    return {
      promotor_id: 0,
      idProvincia: 0,
      nombre:"",
      apellido:"",
      ci:0,
      direccion:"",
      telefono:"",
      email:"",
      provincia: "",
      departamento:"",
      arrayData: [],
      arrayDetalle: [],
      arrayProvincia: [],
      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorMostrar: 0,
      errorMostrarMsj: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 4,
      criterio: "nombre",
      criterioP: "provincia",
      buscarP: "",
      buscar: ""
    };
    },
    computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  methods: {
    listar(page, buscar, criterio) {
      let me = this;
      var url = "/promotor?page=" + page + "&buscar=" + buscar+"&criterio="+criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.promotores.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    selectProvincia(search, loading) {
      let me = this;
      loading(true);
      var url = "/provincia/select?buscar=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayProvincia= respuesta.provincias;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosProvincia(val1) {
      let me = this;
      me.loading = true;
      me.idProvincia = val1.id;
      me.provincia = val1.provincia;
      me.departamento=val1.departamento;
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      // actualizar la Pagina
      me.pagination.current_page = page;
      // enviar la peticion para visualizar la data de esta pagina
      me.listar(page, buscar, criterio);
    },
    encuentra(id) {
      var sw = false;
      for (var i = 0; i < this.arrayDetalle.length && sw==false; i++) {
        if (this.arrayDetalle[i].id == id) {
          sw = true;
        }
      }
      return sw;
    },
    eliminarDetalle(index) {
      let me = this;
      me.arrayDetalle.splice(index, 1);
    },
    agregarDetalle() {
      let me = this;
      if (me.idProvincia == 0) {
         Swal.fire({
              position: "center",
              title: "Error !!",
              type: "error", 
              showConfirmButton: false,
              timer: 1000
            });
      } else {
        if (me.encuentra(me.idProvincia)) {
          Swal.fire({
              position: "center",
              title: "Se Encuentra Agregado",
              type: "error",
              showConfirmButton: false,
              timer: 1000
            });
        } else {
          me.arrayDetalle.push({
            id: me.idProvincia,
            departamento: me.departamento,
            provincia: me.provincia
          });
        }
      }
    },
    agregarDetalleModal(data = []) {
      let me = this;
      if (me.encuentra(data["id"])) {
        Swal.fire({
              position: "center",
              title: "Se Encuentra Agregado",
              type: "error",
              showConfirmButton: false,
              timer: 1000
            });
      } else {
        me.arrayDetalle.push({
          id: data["id"],
          departamento: data["departamento"],
          provincia: data["provincia"],
        });
      }
    },
    listarProvincia(buscar, criterio) {
      let me = this;
      var url = "/provincia/listar?buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayProvincia = respuesta.provincias;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    registrar() {
      if (this.validar()) {
        return;
      }
      let me = this;

      axios
        .post("/promotor/registrar", {
          nombre: this.nombre,
          apellido: this.apellido,
          ci: this.ci,
          direccion: this.direccion,
          telefono: this.telefono,
          email: this.email,
          data: this.arrayDetalle
        })
        .then(function(response) {
          me.listado = 1;
          me.listar(1, "", "nombre");
          this.limpiarRegistro();
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
        .put("/promotor/actualizar", {
          nombre: this.nombre,
          apellido: this.apellido,
          ci: this.ci,
          direccion: this.direccion,
          telefono: this.telefono,
          email: this.email,
          id:this.promotor_id,
          data: this.arrayDetalle
        })
        .then(function(response) {
          me.listado = 1;
          me.listar(1, "", "nombre");
          me.limpiarRegistro();
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
              .put("/promotor/desactivar", {
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
              .put("/promotor/activar", {
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
      this.errorMostrar = 0;
      this.errorMostrarMsj = [];

      if (this.nombre == "" )
        this.errorMostrarMsj.push("Ingrese Nombre");
        if (this.apellido == "" )
        this.errorMostrarMsj.push("Ingrese Apellido");
      if (this.arrayDetalle.length <= 0)
        this.errorMostrarMsj.push("Ingrese Pronvincias al Detalle");

      if (this.errorMostrarMsj.length) this.errorMostrar = 1;
      return this.errorMostrar;
    },
    limpiarRegistro()
    { 
      this.promotor_id=0;
      this.nombre = "";
      this.apellido="";
      this.ci = 0;
      this.telefono = 0;
      this.direccion = "";
      this.email = "";
      this.arrayDetalle=[];
      this.arrayProvincia = [];
    },
    mostrarDetalle(modelo, accion, data = []) {
      switch (modelo) {
        case "promotor": {
          switch (accion) {
            case "registrar": {
              this.listado = 0;
              this.limpiarRegistro();
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.listado = 0;
              this.tipoAccion = 2;
              this.promotor_id = data["id"];
              this.nombre = data["nombre"];
              this.apellido = data["apellido"];
              this.ci=data["ci"];
              this.telefono = data["telefono"];
              this.email = data["email"];
              this.direccion = data["direccion"];
              let me = this;
              var url = "/promotor/listarDetalle?id=" + data["id"];
              axios
                .get(url)
                .then(function(response) {
                  var respuesta = response.data;
                  me.arrayDetalle = respuesta.detalles;
                })
                .catch(function(error) {
                  console.log(error);
                });

              break;
            }
          }
        }
      }
    },
    ocultarDetalle() {
      this.listado = 1;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
    },
    abrirModal() {
      this.arrayProvincia = [];
      this.modal = 1;
      this.tituloModal = "Selecione una o varias Cuentas";
    }
  },
  mounted() {
    this.listar(1, this.buscar, this.criterio);
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
@media (min-width: 600px) {
  .btnagregar {
    margin-top: 2rem;
  }
}
</style>

