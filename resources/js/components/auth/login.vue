<template>
    <div>
        <div>
            <errors :errors="validationErrors"></errors>
            <div v-if="this.$store.state.logged_in === true && logSuccessfull === false">
                <p class="lead">Currently logged in as {{ user.name }}</p>
            </div>
            <button v-if="this.$store.state.logged_in === true" @click="logout" class="btn btn-danger">Log Out</button>
            <div v-if="this.$store.state.logged_in === false">
                <div class="form-row">
                    <div class="col-auto">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Email" v-model="email_login">
                    </div>
                    <div class="col-auto">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" v-model="password_login">
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" v-on:click="login">
                        Login
                    </button>
                    <button type="submit" class="btn btn-danger" v-on:click="cancelLogin">
                        Cancel
                    </button>
                    <a class="btn btn-link" href="">Forgot Your Password?
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    //Completed US3
    import errors from '../utils/errors.vue';

    export default {
        components: {errors},
        data:function(){
            return{
                email_login: '',
                password_login: '',
                validationErrors: 'tou',
                logSuccessfull: false,
            }
        },
        methods:{
            login:function(){
                axios.post('/api/login',{
                    'email_login': this.email_login,
                    'password_login' : this.password_login
                }).then(response=>{
                    axios.defaults.headers.common.Authorization = "Bearer " + Object.values(response.data)[2].toString();
                    this.$store.commit('setToken', Object.values(response.data)[2].toString());
                    this.$store.commit('setLoggedIn');
                    this.$store.dispatch('setData')
                    this.$toasted.show('Successfully logged in!', { type: 'success' });
                    this.$socket.emit('login', this.email_login);
                }).catch(error => {
                    if (error.response.status === 422){
                        this.$toasted.show('Error logging in!', { type: 'error' });
                        this.validationErrors = error.response.data.errors;
                    }
                });
            },
            cancelLogin: function(){
                this.$emit('cancel-login');
            },
            logout: function(){
                this.$socket.emit('logout', this.user.email);
                this.logSuccessfull = false;
                this.cancelLogin();
                axios.post('/api/logout')
                    .then(response => {this.$toasted.show('Successfully logged out!', { type: 'success' });})
                    .catch(error => {this.$toasted.show('Error logging out!', { type: 'error' }); error.message});
                this.$store.commit('logout');
            }
        },
        computed:{
            user: function(){
                return this.$store.state.user;
            },
        }
    }
</script>

<style>

</style>
