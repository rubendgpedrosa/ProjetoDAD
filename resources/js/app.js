/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import VueRouter from 'vue-router';
import vuetify from "../plugins/vuetify";
import Wallets from './components/wallets/wallet.vue';
import Login from './components/auth/login';
import Home from './components/home';
import Users from './components/users/user';
import Categories from './components/category/category';

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);
Vue.component('wallets', Wallets);
Vue.component('home',Home);
Vue.component('login',Login);
Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);
Vue.component('user',Users);
Vue.component('category', Categories);
Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

const routes = [
    {
        path: '/categories', component:Categories
    },
    {
        path: '/users',component:Users
    },{
        path:'/',component:Login
    },{
        path:'/home',component:Home
    },
    {
        path: '/wallet/:id', component: Wallets
    }
];

const router = new VueRouter({routes});

const app = new Vue({

    el: '#app',
    router,
    vuetify

    }
);
