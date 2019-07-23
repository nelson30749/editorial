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
          <i class="fa fa-align-justify"></i> Reporte Promotor
          <!-- <button
            type="button"
            @click="mostrarDetalle()"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button> -->
        </div>
        <!---Listado -->

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
                  <th>Promotor</th>
                  <th>Monto Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in arrayData" :key="data.id">
                  <td>
                    <span class="badge badge-success" v-text="data.id"></span>
                  </td>
                  <td v-text="data.promotor"></td>
                  <td v-text="data.pagado"></td>
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
       
        <!--Fin de Detalle -->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
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
      plan_id: 0,
      idCuota: 0,
      promotor: "",
      montoTotal: 0,
      monto:0,
      arrayData: [],
      arrayDetalle: [],
      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorMostrar: 0,
      errorMostrarMsj: [],
      pago:1,
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
      buscar: "",
      sumaCuota:0
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
      var url = "/reporte/promotor?page=" + page + "&buscar=" + buscar+"&criterio="+criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayData = respuesta.plan_pagos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      // actualizar la Pagina
      me.pagination.current_page = page;
      // enviar la peticion para visualizar la data de esta pagina
      me.listar(page, buscar, criterio);
    },
    registrar() {
      let me = this;
      if (me.validar()) {
        return;
      }
      axios
        .post("/plan_pago/registrar", {
          idPlan: this.plan_id,
          monto:this.monto
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarDetalle(me.plan_id);
          me.monto=0;
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
        .put("/plan_pago/actualizar", {
          idPlan: this.plan_id,
          monto:this.monto,
          id:this.idCuota
        })
        .then(function(response) {
          me.listado = 1;
          me.listarDetalle(this.plan_id);
          me.monto=0;
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
              .put("/cuota/desactivar", {
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
              .put("/cuota/activar", {
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
      if (this.monto == 0  || this.monto=="")
        this.errorMostrarMsj.push("Ingrese el Monto");

      if((this.montoTotal-this.sumaCuota)<this.monto)
      this.errorMostrarMsj.push("El Monto no Puede se mas que el Saldo Restante");

      if (this.errorMostrarMsj.length) this.errorMostrar = 1;
      return this.errorMostrar;
    },
    limpiarRegistro()
    { 
      this.monto=0;
      this.promotor="";
      this.plan_id=0;
      this.idCuota=0;
      this.arrayDetalle=[];
    },
    mostrarDetalle(data = []) {
              this.listado = 0;
              this.tipoAccion = 2;
              this.plan_id = data["id"];
              this.promotor = data["promotor"];
              this.montoTotal = data["montoTotal"];
              this.pago=data["pago"];
              this.listarDetalle(data["id"]);             
    },
    listarDetalle(id)
    {
      let me = this;
              var url = "/plan_pago/listarDetalle?id=" +id;
              axios
                .get(url)
                .then(function(response) {
                  var respuesta = response.data;
                  me.arrayDetalle = respuesta.detalles;
                  me.sumaCuota=respuesta.sumaCuota[0].montoCuota;
                  me.pago=respuesta.sumaCuota[0].pago;
                })
                .catch(function(error) {
                  console.log(error);
                });
    },
    ocultarDetalle() {
      this.listado = 1;
      this.limpiarRegistro();
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
    },
    abrirModal() {
      this.monto=0;
      this.arrayLibro= [];
      this.modal = 1;
      this.tituloModal = "Ingresar Cuota";
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

