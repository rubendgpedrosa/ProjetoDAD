
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VueRouter from 'vue-router';
import Wallets from './components/wallets/wallet';
import Categories from './components/category/category'

Vue.component('wallets', Wallets);
Vue.component('category', Categories);


Vue.use(VueRouter);

const routes = [
    {
        path: '/categories', component:Categories
    },
    {
        path: '/wallet/:id', component: Wallets
    }
];

const router = new VueRouter({routes});


const app = new Vue({
    el: '#app',
    router:router,
});
