
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.flatPickr = require('vue-flatpickr-component');
window.VueSwal = require('vue-swal');
window.vSelect = require('vue-select');
require('flatpickr/dist/flatpickr.css');
require('flatpickr/dist/l10n/es.js');
Vue.use(flatPickr);
Vue.use(vSelect);
Vue.use(VueSwal);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('actividadesusuario', require('./components/ActividadesUsuario.vue').default);
Vue.component('v-select', vSelect);


const opt = new Vue({
    el: '#activi',
    data: {
    	usuario: [],
    },
});
