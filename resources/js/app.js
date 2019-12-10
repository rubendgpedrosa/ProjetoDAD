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

import RegisterExpense from './components/expense/registerExpense.vue'
import RegisterUser from './components/users/registerUser';
import Categories from './components/category/category';
import VueRouter from 'vue-router';
import Homepage from './components/homepage/homepage.vue';
import vuetify from "../plugins/vuetify";
import Wallets from './components/wallets/wallet.vue';
import Login from './components/auth/login';
import Users from './components/users/user';
import Home from './components/home';

Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('RegisterExpense', RegisterExpense);
Vue.component('RegisterUser', RegisterUser);
Vue.component('category', Categories);
Vue.component('Homepage', Homepage);
Vue.component('wallets', Wallets);
Vue.component('login', Login);
Vue.component('user', Users);
Vue.component('home', Home);

Vue.use(VueRouter);

const routes = [
    {
        path: '/categories', component:Categories
    },
    {
        path: '/users',component:Users
    },
    {
        path:'/login',component:Login
    },
    {
        path:'/Home',component:Home
    },
    {
        path: '/wallets/:id',
        component: Wallets
    },
    {
        path: '/',
        component: Homepage
    },
    {
        path: '/expenses',
        component: RegisterExpense
    }
];

const router = new VueRouter({routes});

const app = new Vue({
    el: '#app',
    //mode: 'history',
    router,
    vuetify
    }
);
