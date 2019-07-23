/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('proveedor', require('./components/Proveedor.vue').default);
Vue.component('departamento', require('./components/Departamento.vue').default);
Vue.component('libro', require('./components/Libro.vue').default);
Vue.component('ingreso', require('./components/Ingreso.vue').default);
Vue.component('provincia', require('./components/Provincia.vue').default);
Vue.component('promotor', require('./components/Promotor.vue').default);
Vue.component('entrega', require('./components/Entrega.vue').default);
Vue.component('plan_pago', require('./components/PlanPago.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('reporte', require('./components/Reporte.vue').default);

const app = new Vue({
    el: '#app',
    data :{
      menu : 0
    }
});