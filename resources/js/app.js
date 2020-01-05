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

import expenseRegistrationOperator from "./components/expense/incomeRegistrationOperator";
import RegisterExpense from './components/expense/registerExpense.vue';
import RegisterAdmin from './components/users/registerAdmin.vue';
import ChartContainer from './components/statistics/ChartContainer';
import RegisterUser from './components/users/registerUser';
import adminNavBar from "./components/utils/adminNavBar";
import VueSocketIO from "vue-socket.io";
import Categories from './components/category/category';
import userNavBar from './components/utils/userNavBar.vue';
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
Vue.component('expenseRegistrationOperator', expenseRegistrationOperator);
Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('RegisterExpense', RegisterExpense);
Vue.component('ChartContainer', ChartContainer);
Vue.component('RegisterAdmin', RegisterAdmin);
Vue.component('RegisterUser', RegisterUser);
Vue.component('userNavBar', userNavBar);
Vue.component('adminNavBar', adminNavBar);
Vue.component('category', Categories);
Vue.component('Homepage', Homepage);
Vue.component('wallets', Wallets);
Vue.component('login', Login);
Vue.component('user', Users);

Vue.component('home', Home);
Vue.use(VueRouter);
Vue.use(Vuex);

const socket = new VueSocketIO({
    debug: true,
    connection: 'http://127.0.0.1:8080'
});
Vue.use(socket);

const routes = [
    {
        path: '/',
        component: Homepage
    },
    {
        path: '/users',
        component:Users,
        meta: { requiresAuth: true, requiresPowers: true}
    },
    {
        path:'/Home',
        component:Home,
        meta: { requiresAuth: true }
    },
    {
        path: '/wallet',
        component: Wallets,
        meta: { requiresAuth: true, requiresWallet: true }
    },
    {
        path: '/expenses',
        component: RegisterExpense,
        meta: { requiresAuth: true, requiresWallet: true }
    },
    {
        path: '/profile',
        component: userNavBar,
        meta: { requiresAuth: true }
    },
    {
        path: '/deposits',
        component: expenseRegistrationOperator,
        meta: { requiresAuth: true, requiresOperatorPowers: true }
    },
    {
        path: '/admin',
        component: adminNavBar,
        meta: { requiresAuth: true, requiresPowers: true }
    }
];

const router = new VueRouter({routes});

Vue.prototype.$bus = new Vue({});
Vue.prototype.$eventHub = new Vue(); // Global event bus

import Toasted from "vue-toasted";

Vue.use(Toasted, {
    position: "bottom-right",
    duration: 5000,
});

const store = new Vuex.Store({
    state: {
        walletID: '',
        token: '',
        email: '',
        logged_in: false,
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
        userStatistics: {},
        adminStatistics: {},
    },
    mutations: {
        setToken(state, token){
            state.token = 'Bearer '+ token;
        },
        setWalletData(state, { data }){
            state.number_wallets = data;
        },
        setWallet(state, { data }){
            state.wallet = data;
        },
        setMovements(state, { data }){
            state.movements = data;
        },
        tokenAuth(state, {data}){
            state.token = data;
        },
        setCategories(state, { data }){
            state.categories = data;
        },
        setUser(state, { data }){
            state.user = data;
        },
        setUsers(state, { data }){
            state.users = data;
        },
        addMovement(state, {data}){
            let newDate = data.date.date;
            data.date = newDate.toString().split('.')[0];
            state.movements.unshift(data);
        },
        setWalletsEmail(state, { data }){
            state.walletsEmail = data;
        },
        setWalletsEmailArray(state, email){
            state.walletsEmailArray.push(email);
        },
        addUser(state, {data}){
            state.users.push(data);
        },
        changeUser(state, {data}){
            let userIndex = state.users.findIndex(user => user.id === data.id);
            state.users[userIndex].name = data.name;
        },
        setLoggedIn(state){
            state.logged_in = true;
        },
        logout (state) {
            state.logged_in = false;
            state.user = {};
            state.wallet = {};
            state.movements = [{}];
            state.walletID = '';
            state.email = '';
            state.users = '';
        },
        resetErrorLogged(state){
            state.errorNotLogged = false;
        },
        resetErrorNotAdmin(state){
            state.errorNotAdmin = false;
        },
        resetErrorNotOperator(state){
            state.errorNotOperator = false;
        }
    },
    actions:{
        updateData(){
            let headerData = {Accept: 'Application/json',Authorization: store.state.token};
            axios.get(`api/movements/${store.state.user.id}`, { headers: headerData})
                .then(response=>{ store.state.movements = response.data.reverse();})
                .catch( error => { console.log(error.message); });
            axios.get(`/api/wallet/${store.state.user.id}`, { headers: headerData})
                .then(response => {store.state.wallet = response.data;})
                .catch( error => { console.log(error.message);});
        },
        setData(context){
            let headerData = {Accept: 'Application/json',Authorization: store.state.token};
            axios.get('api/user', { headers: headerData}).then(response => {
                context.commit('setUser',response.data);
                store.state.user = response.data;
                if(response.data.type === 'u'){
                    axios.get(`api/movements/${response.data.id}`, { headers: headerData})
                        .then(response=>{ store.state.movements = response.data.reverse();})
                        .catch( error => { console.log(error.message); });
                    axios.get(`/api/wallet/${response.data.id}`, { headers: headerData})
                        .then(response => {store.state.wallet = response.data;})
                        .catch( error => { console.log(error.message); });
                    axios.get(`api/userStatistics/${response.data.id}`, {headers: headerData})
                        .then(response => {store.state.userStatistics = response.data})
                        .catch( error => { console.log(error.message); });
                }
                if(response.data.type === 'a'){
                    axios.get('api/statistics', {headers: headerData})
                        .then(response=>{ store.state.adminStatistics = response.data})
                        .catch( error => { console.log(error.message); });
                    axios.get('api/users', { headers: headerData})
                        .then(response=>{ store.state.users = response.data })
                        .catch( error => { console.log(error.message); });
                }
            })
                .catch( error => { console.log(error.message); });
            if(store.state.walletsEmailArray.length === 0) {
                axios.get('api/walletsEmail', {headers: headerData}).then(response => {
                    store.state.walletsEmail = response.data;
                    response.data.forEach(element => {
                        store.state.walletsEmailArray.push(element.email);
                    });
                });
            }
            axios.get('api/categories', {headers: headerData})
                .then(response => {
                    store.state.categories = response.data.data;
                })
                .catch(error => {
                    console.log(error.message);
                });
        },
        isAuthenticated () {
            return store.state.logged_in;
        },
    }
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.state.logged_in) {
            Vue.toasted.show('You must be logged in to access that!', { type: 'error' });
            next('/');
        }
        else {
            if (to.matched.some(record => record.meta.requiresPowers)){
                if(store.state.user.type === 'a'){
                    next();
                }else{
                    Vue.toasted.show('You must be an Administrator to access that!', { type: 'error' });
                    next(false);
                }
            }else{
                if(to.matched.some(record => record.meta.requiresWallet)){
                    if(store.state.wallet.id !== undefined){
                        next();
                    }else{
                        Vue.toasted.show('You must be a platform user with a wallet to acess that!', { type: 'error' });
                        next(false);
                    }
                }else {
                    if (to.matched.some(record => record.meta.requiresOperatorPowers)){
                        if(store.state.user.type === 'o'){
                            next();
                        }else{
                            Vue.toasted.show('You must be an Operator to access that!', { type: 'error' });
                            next(false);
                        }
                    }else{
                        next();
                    }
                }
            }
        }
    } else {
        next();
    }
});

const app = new Vue({
        el: '#app',
        router,
        vuetify,
        store,
        methods:{
            getNumberWallets: function(){
                axios.get('/api/wallets')
                    .then(response => (this.$store.commit('setWalletData', response)))
                    .catch(error=>console.log(error.nessage));
            },
            logout(){
                this.$socket.emit('logout', this.$store.state.user.email);
                console.log(this.$route.path);
                axios.post('/api/logout')
                    .then(response => {this.$toasted.show('Successfully logged out!', { type: 'success' });
                        if(this.$route.path !== '/'){
                            this.$router.push('/');
                        }
                        this.$store.commit('logout');
                    })
                    .catch(error => {this.$toasted.show('Error logging out!', { type: 'error' }); console.log(error.response.data)});
            }
        },
        created (){
            this.getNumberWallets();
        },
        sockets: {
            wallet_movements_response: function(){
                this.$store.dispatch('updateData');
                this.$toasted.show('Deposit made to your account!', { type: 'success' });
            }
        }
    }
);
