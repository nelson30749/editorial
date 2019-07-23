<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <div class="card">
        <div class="card-header"></div>
        <div class="car-body">
          <div class="row">
            <div class="col-md-6">
              <div class="card card-chart">
                <div class="card-header">
                  <h4>Ingresos</h4>
                </div>
                <div class="card-content">
                  <div class="ct-chart">
                    <canvas id="ingresos"></canvas>
                  </div>
                </div>
                <div class="card-footer">
                  <p>Ingresos de los últimos meses.</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-chart">
                <div class="card-header">
                  <h4>Entregas</h4>
                </div>
                <div class="card-content">
                  <div class="ct-chart">
                    <canvas id="entregas"></canvas>
                  </div>
                </div>
                <div class="card-footer">
                  <p>Entregas de los últimos meses.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
// import VueCharts from 'vue-chartjs'
// import { Bar, Line } from 'vue-chartjs'
import Chart from "chart.js";
import Vue from "vue";
export default {
  data() {
    return {
      varIngreso: null,
      charIngreso: null,
      ingresos: [],
      varTotalIngreso: [],
      varMesIngreso: [],

      varEntrega: null,
      charEntrega: null,
      entregas: [],
      varTotalEntrega: [],
      varMesEntrega: []
    };
  },
  methods: {
    getIngresos() {
      let me = this;
      var url = "/dashboard";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.ingresos = respuesta.ingresos;
          //cargamos los datos del chart
          me.loadIngresos();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    loadIngresos() {
      let me = this;
      me.ingresos.map(function(x) {
        me.varMesIngreso.push(x.mes);
        me.varTotalIngreso.push(x.total);
      });
      me.varIngreso = document.getElementById("ingresos").getContext("2d");

      me.charIngreso = new Chart(me.varIngreso, {
        type: "bar",
        data: {
          labels: me.varMesIngreso,
          datasets: [
            {
              label: "Ingresos",
              data: me.varTotalIngreso,
              backgroundColor: "#fd324e",
              borderColor: "#fd324e",
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ]
          }
        }
      });
    },
    getEntregas() {
      let me = this;
      var url = "/dashboard";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.entregas = respuesta.entregas;
          //cargamos los datos del chart
          me.loadEntregas();
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    loadEntregas() {
      let me = this;
      me.entregas.map(function(x) {
        me.varMesEntrega.push(x.mes);
        me.varTotalEntrega.push(x.total);
      });
      me.varEntrega = document.getElementById("entregas").getContext("2d");

      me.charEntrega = new Chart(me.varEntrega, {
        type: "bar",
        data: {
          labels: me.varMesEntrega,
          datasets: [
            {
              label: "Entregas",
              data: me.varTotalEntrega,
              backgroundColor: "#FBF421",
              borderColor: "#fd324e",
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ]
          }
        }
      });
    }
  },
  mounted() {
    this.getIngresos();
    this.getEntregas();
  }
};
</script>