<template>
    <div>
        <div class = jumbotron>
            <h1>LOGIN</h1>
            <hr class="my-4">
            <div v-if="this.$store.state.logged_in === true">
                <p class="lead">Currently logged in as {{ this.$store.state.user.name }}</p>
                <button @click="logout" class="btn btn-danger">Log Out</button>
            </div>
            <div v-if="this.$store.state.logged_in === false">
                <div class="form-row">
                    <div class="col-auto">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Email" v-model="email">
                    </div>
                    <div class="col-auto">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" v-model="password">
                    </div>
                </div>
                <div class="form-row">
                    <button type="submit" class="btn btn-primary" v-on:click="login">
                        Login
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
export default {
    data:function(){
        return{
            email: '',
            password: '',
        }
    },
    methods:{
        login:function(){
            axios.post('/api/login',{
                'email': this.email,
                'password' : this.password
            }).then(response=>{
                //TODO login with vuex
                sessionStorage.setItem('tokenAuth',Object.values(response.data[0])[2].toString());
                this.$store.commit('setEmail', this.email);
                //sessionStorage.setItem('email', this.email);
                if(response.data[2] !== null){
                    this.$store.commit('setUserData', response.data[1]);
                    //sessionStorage.setItem('walletID', response.data[1]);
                }
                this.$store.commit('setLoggedIn');
                //sessionStorage.setItem('userID', response.data[2]);
                axios.defaults.headers.common.Authorization = "Bearer " +sessionStorage.getItem('tokenAuth');
                //var token = sessionStorage.getItem('tokenAuth');
                //this.$bus.$emit('logged-in', {data: this.email});
                //this.$eventHub.$emit('logged-in', this.email);
            }).catch(error=>{
                console.log(error);
            })
        },
        logout: function(){
            this.$store.commit('reset');
            sessionStorage.clear();
        }
    }
}
</script>

<style>

</style>
