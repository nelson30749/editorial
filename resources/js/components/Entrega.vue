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
          <i class="fa fa-align-justify"></i> Entrega
          <button
            type="button"
            @click="mostrarDetalle('entrega','registrar')"
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
                    <option value="nombre">Promotor Nombre</option>
                    <option value="apellido">Promotor Apellido</option>
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
                  <th>N°</th>
                  <th>Promotor</th>
                  <th>Fecha</th>
                  <th>Comprobante</th>
                  <th>Cantidad</th>
                  <th>Monto Total</th>
                  <th>Entrega</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in arrayData" :key="data.id">
                  <td>
                    <span class="badge badge-success" v-text="data.id"></span>
                  </td>
                  <td v-text="data.nro"></td>
                  <td v-text="data.promotor"></td>
                  <td v-text="data.fecha"></td>
                  <td v-text="data.comprobante"></td>
                  <td v-text="data.cantidad"></td>
                  <td v-text="data.montoTotal"></td>
                  <td>
                      <div v-if="data.pago">
                        <span class="badge badge-success">Contado</span>
                      </div>
                      <div v-else>
                        <span class="badge badge-success">Credito</span>
                      </div>
                    </td>
                  <td>
                    <div v-if="data.estado">
                      <span class="badge badge-success">Activo</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-danger">Desactivado</span>
                    </div>
                  </td>
                  <td>
                    <!-- <button
                      type="button"
                      @click="mostrarDetalle('entrega','actualizar',data)"
                      class="btn btn-warning btn-sm"
                    >
                      <i class="icon-pencil"></i>
                    </button> &nbsp; -->
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
                  <label>
                    Proomotor
                    <span v-show="promotor==''" class=" text-error">(*Selecione)</span>
                  </label>
                  <v-select
                    v-model="selectedPromotor"
                    @search="selectPromotor"
                    label="promotor"
                    :options="arrayPromotor"
                    placeholder="Buscar Promotor.."
                    @input="getDatosPromotor"
                  ></v-select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for>Entrega 
                     <span v-show="pago==2" class=" text-error">(*Selecione)</span>
                  </label>
                  <select class="form-control col-md-12" v-model="pago">
                    <option value=2 >Seleccione</option>
                    <option value=1>Contado</option>
                    <option value=0>Credito</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for>
                    Comprobante
                    <br>
                  </label>
                  <input
                    type="text"
                    v-model="comprobante"
                    class="form-control"
                    placeholder="Comprobante.."
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
            <template v-if="pago==0">
               <div class="form-group row border">
              <div class="col-md-4">
                <div class="form-group">
                  <label>
                    Fecha
                    <span v-show="fechaInicio==''">(*Selecione)</span>
                  </label>
                  <input type="date" class="form-control" v-model="fechaInicio">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>
                    Fecha
                    <span v-show="fechaFin==''">(*Selecione)</span>
                  </label>
                  <input type="date" class="form-control" v-model="fechaFin">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>
                    Monto Pago
                  </label>
                  <input type="number" disabled class="form-control" v-model="montoTotal">
                </div>
              </div>
              <div class="col-md-6" v-if="cuota">
                <div class="form-group">
                  <button @click="cambiarCuota(false)" class="btn btn-success btnagregar">
                    <i class="icon-plus">Agregar Cuota</i>
                  </button>
                </div>
              </div>
              <div class="col-md-6" v-else>
                <div class="form-group">
                  <button @click="cambiarCuota(true)" class="btn btn-danger btnagregar">
                    <i class="icon-close">Quitar Cuota</i>
                  </button>
                </div>
              </div>
              <div class="col-md-12" v-if="cuota==false">
                <div class="form-group">
                  <label for>
                    Monto Cuota
                    <br>
                  </label>
                  <input
                    type="number"
                    v-model="montoCuota"
                    class="form-control"
                    placeholder="Monto de la Cuota.."
                  >
                </div>
              </div>
            </div>
            </template>
            <div class="form-group row border">
              <div class="col-md-6">
                <div class="form-group">
                  <label>
                    Libro
                    <span v-show="libro==''">(*Selecione)</span>
                  </label>
                  <v-select
                    @search="selectLibro"
                    label="nombre"
                    :options="arrayLibro"
                    placeholder="Buscar Libro..."
                    @input="getDatosLibro"
                  ></v-select>
                </div>
              </div>
              <div class="col-md-0">
                <div class="form-group">
                  <button @click="abrirModal()" class="btn btn-success  btnagregar">
                    <i class="icon-plus"></i>
                    <i class="icon-plus"></i>
                    <i class="icon-plus"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for>Cantidad</label>
                  <input type="number" value="0" class="form-control" v-model="cantidad" />
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <button @click="agregarDetalle()" class="btn btn-success btnagregar">
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
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Genero</th>
                      <th>Grado</th>
                      <th>Descripcion</th>
                      <th>Precio</th>
                      <th>Cantidad</th>
                      <th>SubTotal</th>
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

                      <td v-text="detalle.nombre"></td>
                      <td>{{ detalle.genero }}</td>
                      <td v-text="detalle.grado"></td>
                      <td>{{ detalle.descripcion }}</td>
                       <td>
                        <input
                          type="number"
                          min="0"
                          v-model="detalle.precio"
                          @keyup.enter="ingresoTotal()"
                          class="form-control"
                        />
                      </td>
                      <td>
                        <input
                          type="number"
                          min="0"
                          :max="detalle.stock"
                          @keyup.enter="ingresoTotal()"
                          v-model="detalle.cantidad"
                          class="form-control"
                        />
                      </td>
                      <td>{{ (detalle.cantidad*detalle.precio) }}</td>
                    </tr>
                    <tr>
                      <td colspan="7">Total</td>
                      <td>Cantidad: {{ sumaCantidad }}</td>
                      <td>Monto: Bs. {{ montoTotal }}</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="9">No hay Productos Agregados</td>
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
                    <option value="nombre">Nombre</option>
                    <option value="genero">Genero</option>
                    <option value="grado">Grado</option>
                    <option value="descripcion">Descripcion</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscarP"
                    @keyup="listarLibro(buscarP,criterioP)"
                    class="form-control"
                    placeholder="Buscar Producto"
                  />
                  <button
                    type="submit"
                    @click="listarLibro(buscarP,criterioP)"
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
                    <th>Nombre</th>
                    <th>Genero</th>
                    <th>Grado</th>
                    <th>Descripcion</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="libros in arrayLibro" :key="libros.id">
                    <td>
                      <button
                        type="button"
                        @click="agregarDetalleModal(libros)"
                        class="btn btn-success btn-sm"
                      >
                        <i class="icon-check"></i>
                      </button>
                    </td>
                    <td v-text="libros.nombre"></td>
                    <td v-text="libros.genero"></td>
                    <td v-text="libros.grado"></td>
                    <td v-text="libros.descripcion"></td>
                    <td v-text="libros.stock"></td>
                    <td v-text="libros.precio"></td>
                    <td>
                      <div v-if="libros.estado">
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
      entrega_id: 0,
      idPromotor: 0,
      promotor: "",
      cantidad: 0,
      comprobante:"",
      montoTotal: 0,
      arrayData: [],
      arrayDetalle: [],
      arrayLibro: [],
      arrayPromotor: [],
      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorMostrar: 0,
      errorMostrarMsj: [],
      selectedPromotor:null,
      pago:2,
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
      criterioP: "nombre",
      buscarP: "",
      buscar: "",
      libro: "",
      idLibro: 0,
      genero: "",
      grado: "",
      descripcion: "",
      precio: 0,
      stock:0,
      sumaCantidad:0,
      cuota:true,
      montoCuota:0,
      fechaInicio:new Date(),
      fechaFin:new Date()
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
      var url = "/entrega?page=" + page + "&buscar=" + buscar+"&criterio="+criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.entregas.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    selectPromotor(search, loading) {
      let me = this;
      loading(true);
      var url = "/promotor/select?buscar=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayPromotor = respuesta.promotores;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosPromotor(val1) {
      let me = this;
      me.loading = true;
      me.idPromotor = val1.id;
      me.promotor = val1.promotor;
    },
    selectLibro(search, loading) {
      let me = this;
      loading(true);
      var url = "/libro/select?buscar=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayLibro = respuesta.libros;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosLibro(val1) {
      let me = this;
      me.loading = true;
      me.idLibro = val1.id;
      me.libro = val1.nombre;
      me.genero = val1.genero;
      me.grado = val1.grado;
      me.descripcion = val1.descripcion;
      me.precio = val1.precio;
      me.stock=val1.stock;
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
    ingresoTotal() {
      let me = this;
      me.montoTotal = 0;
      me.sumaCantidad = 0;
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (
          me.arrayDetalle[i].cantidad != 0 &&
          me.arrayDetalle[i].cantidad != ""
        ) {
          // Suma Monto Total
          me.montoTotal = me.arrayDetalle[i].cantidad * me.arrayDetalle[i].precio +me.montoTotal;
            // suma Cantidad
          me.sumaCantidad =me.arrayDetalle[i].cantidad*1 + me.sumaCantidad;
        }
      }
      // return me.montoTotal;
      
    },

    agregarDetalle() {
      let me = this;
      if (me.idLibro == 0) {
         Swal.fire({
              position: "center",
              title: "Error !!",
              type: "error", 
              showConfirmButton: false,
              timer: 1000
            });
      } else {
        if (me.encuentra(me.idLibro)) {
          Swal.fire({
              position: "center",
              title: "El Producto ya se Encuentra Agregado",
              type: "error",
              showConfirmButton: false,
              timer: 1000
            });
        } else {
          me.arrayDetalle.push({
            id: me.idLibro,
            nombre: me.libro,
            genero: me.genero,
            grado: me.grado,
            descripcion: me.descripcion,
            precio: me.precio,
            cantidad: me.cantidad,
            stock:me.stock
          });
        }
      }
    },
    agregarDetalleModal(data = []) {
      let me = this;
      if (me.encuentra(data["id"])) {
        Swal.fire({
              position: "center",
              title: "El Producto ya se Encuentra Agregado",
              type: "error",
              showConfirmButton: false,
              timer: 1000
            });
      } else {
        me.arrayDetalle.push({
          id: data["id"],
          nombre: data["nombre"],
          genero: data["genero"],
          grado: data["grado"],
          descripcion: data["descripcion"],
          precio: data["precio"],
          cantidad: 1,
          stock: data["stock"]
        });
      }
    },
    cambiarCuota(cambiar)
    {
      this.cuota=cambiar;
      if(!cambiar)
      {
        this.montoCuota=0;
      }
    },
    listarLibro(buscar, criterio) {
      let me = this;
      var url = "/libro/listar?buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayLibro = respuesta.libros;
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
        .post("/entrega/registrar", {
          idPromotor: this.idPromotor,
          comprobante:this.comprobante,
          cantidad: this.sumaCantidad,
          montoTotal: this.montoTotal,
          pago:this.pago,
          fechaInicio:this.fechaInicio,
          fechaFin:this.fechaFin,
          cuota:this.cuota,
          montoCuota:this.montoCuota,
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
    actualizar() {
      if (this.validar()) {
        return;
      }
      let me = this;

      axios
        .put("/entrega/actualizar", {
           idPromotor: this.idPromotor,
          comprobante:this.comprobante,
          cantidad: this.sumaCantidad,
          montoTotal: this.montoTotal,
          pago:this.pago,
          id:this.entrega_id,
          fechaInicio:this.fechaInicio,
          fechaFin:this.fechaFin,
          cuota:this.cuota,
          montoCuota:this.montoCuota,
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
              .put("/entrega/desactivar", {
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
              .put("/entrega/activar", {
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

      if (this.idPromotor == 0 )
        this.errorMostrarMsj.push("Seleccione al Promotor");
        if (this.pago == 2 )
        this.errorMostrarMsj.push("Seleccione la Entrega");
      if (this.arrayDetalle.length <= 0)
           this.errorMostrarMsj.push("No Tiene Libros Seleccionado al Detalle");
        let sw=true;
      for(var i=0 ;i<this.arrayDetalle.length && sw==true; i++)
      {
        if(this.arrayDetalle[i].cantidad>this.arrayDetalle[i].stock)
        {
          this.errorMostrarMsj.push("La Cantidad del Libro "+this.arrayDetalle[i].nombre +" es Mayor que el Stock");
          sw=false;
        }
      }

      if (this.errorMostrarMsj.length) this.errorMostrar = 1;
      return this.errorMostrar;
    },
    limpiarRegistro()
    { 
      this.promotor = "";
      this.selectedPromotor=null;
      this.pago=2;
      this.idPromotor = 0;
      this.cantidad = 0;
      this.montoTotal = 0;
      this.comprobante="";
      this.descripcion = "";
      this.genero="";
      this.grado="";
      this.idLibro=0;
      this.libro="";
      this.arrayPromotor=[];
      this.arrayDetalle = [];
      this.arrayPromotor=[];
    },
    mostrarDetalle(modelo, accion, data = []) {
      switch (modelo) {
        case "entrega": {
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
              this.entrega_id = data["id"];
              this.idPromotor = data["idPromotor"];
              this.selectedPromotor={id:data["idPromotor"],promotor:data["promotor"]}
              this.promotor = data["promotor"];
              this.comprobante=data["comprobante"];
              this.sumaCantidad=data["cantidad"];
              this.pago= data["pago"];
              this.montoTotal = data["montoTotal"];

              let me = this;
              var url = "/entrega/listarDetalle?id=" + data["id"];
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
      this.arrayLibro= [];
      this.modal = 1;
      this.tituloModal = "Selecione una o varias Libros";
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

