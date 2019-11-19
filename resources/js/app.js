
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import Wallets from './components/wallets/wallet.vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);
Vue.component('wallets', require('./components/wallets/wallet.vue').default);


import Login from './components/users/login';
Vue.component('login',Login);
import Users from './components/users/user';
Vue.component('user',Users);
import Categories from './components/category/category';
Vue.component('category', Categories);
const routes = [
    { path: '/wallet/:id', component: Wallets },
    {
        path: '/categories', component:Categories
    },
    {
        path: '/users',component:Users
    },
    {
        path:'/',component:Login
    }
];

const router = new VueRouter({routes});

const app = new Vue({
    el: '#app',
    router
    }
);
