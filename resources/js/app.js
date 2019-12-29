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

import RegisterExpense from './components/expense/registerExpense.vue';
import RegisterUser from './components/users/registerUser';
import UserProfile from './components/profile/userProfile';
import Categories from './components/category/category';
import VueRouter from 'vue-router';
import Homepage from './components/homepage/homepage.vue';
import vuetify from "../plugins/vuetify";
import Wallets from './components/wallets/wallet.vue';
import Login from './components/auth/login';
import Users from './components/users/user';
import Home from './components/home';
import Vuex from 'vuex';
import Vue from "vue";

Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('RegisterExpense', RegisterExpense);
Vue.component('RegisterUser', RegisterUser);
Vue.component('UserProfile', UserProfile);
Vue.component('category', Categories);
Vue.component('Homepage', Homepage);
Vue.component('wallets', Wallets);
Vue.component('login', Login);
Vue.component('user', Users);
Vue.component('home', Home);

Vue.use(VueRouter);

Vue.use(Vuex);


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
        path: '/wallet/',
        component: Wallets
    },
    {
        path: '/',
        component: Homepage
    },
    {
        path: '/expenses',
        component: RegisterExpense
    },
    {
    //TODO test path for now
        path: '/profile',
        component: UserProfile
    }
];

const router = new VueRouter({routes});

Vue.prototype.$bus = new Vue({})
Vue.prototype.$eventHub = new Vue(); // Global event bus

const store = new Vuex.Store({
    state: {
        walletID: '',
        email: '',
        token: {},
        number_wallets: '',
        categories: [{}],
        walletsArray: [{}],
        walletsEmail: [],
        walletsEmailArray: [],
        usersArray: [{}],
        movements: [{}],
        user: {},
        users: [{}],
        wallet: {},
    },
    mutations: {
        setWalletData(state, { data }){
            state.number_wallets = data;
        },
        tokenAuth(state, {data}){
            state.token = data;
        },
        setUserData(state, id){
            state.walletID = id;
            axios.get(`api/movements/${id}`)
                .then(response=>{ state.movements = response.data});
            axios.get(`/api/users/${id}`)
                .then(response => {state.user = response.data.data;});
            axios.get(`/api/wallet/${(id)}`)
                .then(response => {state.wallet = response.data;});
        },
        setEmail(state, email){
            state.email = email;
        },
        setCategories(state, {data}){
            state.categories = data;
        },
        setWalletsEmail(state, {data}){
            state.walletsEmail = data;
        },
        setWalletsEmailArray(state, email){
            state.walletsEmailArray.push(email);
        },
        setUsers(state, {data}){
            state.users = data;
        }
    }
})


const app = new Vue({
        el: '#app',
        //mode: 'history',
        router,
        vuetify,
        store,
        methods:{
            getNumberWallets: function(){
                axios.get('/api/wallets')
                    .then(response => (this.$store.commit('setWalletData', response)))
                    .catch(error=>console.log(error.nessage));
            },
            getCategories: function () {
                axios.get('api/categories')
                    .then(response=>{this.$store.commit('setCategories', response.data)});
            },
            getWalletsEmail: function(){
                axios.get('api/walletsEmail').then(response => {
                    this.$store.commit('setWalletsEmail', response.data);
                    response.data.forEach(element => {
                        this.$store.commit('setWalletsEmailArray', element.email);
                    });
                });
            },
            getUsers: function () {
                axios.get('api/users')
                    .then(response=>{ this.$store.commit('setUsers',response)});
            }
        },
        created (){
            this.getNumberWallets();
            this.getCategories();
            this.getWalletsEmail();
            this.getUsers();
        }
    }
);
