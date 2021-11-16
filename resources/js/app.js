/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import store from './store.js'
import VueResource from "vue-resource"

import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css'

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
Vue.component('nav-component', require('./components/itemcomponents/navitem.vue').default);
Vue.component('postbody-component', require('./components/postpage/PageBody.vue').default);
Vue.component('planoutline-post-component', require('./components/postpage/PlanOutlinePost.vue').default);
Vue.component('planoutline-view-component', require('./components/postpage/PlanOutlineView.vue').default);
Vue.component('spot-post-component', require('./components/postpage/SpotPost.vue').default);
Vue.component('spot-view-component', require('./components/postpage/SpotView.vue').default);
Vue.component('planitem-component', require('./components/itemcomponents/planitem.vue').default);
Vue.component('spotitem-component', require('./components/itemcomponents/spotitem.vue').default);
Vue.component('useritem-component', require('./components/itemcomponents/useritem.vue').default);
Vue.component('searchbody-component', require('./components/searchpage/searchbody.vue').default);
Vue.use(VueResource);
Vue.use(Vuetify);
// Vue.component('postpage-component', require('./components/PostPageComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



const app = new Vue({
    el: '#app',
    store: store,
    vuetify: new Vuetify(),
    components: {
    }
});
