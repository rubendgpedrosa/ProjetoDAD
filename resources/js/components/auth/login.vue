<template>
    <div>
        <div class = jumbotron>
            <h1>LOGIN</h1>
            <hr class="my-4"></hr>
            <div v-if="logged !== null">
                <p class="lead">Currently Logged with {{ logged_email }}</p>
                <button @click="logout" class="btn btn-danger">Log Out</button>
            </div>
            <div v-if="logged === null">
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
export default {
    data:function(){
        return{
            email: '',
            password: '',
            logged: sessionStorage.getItem('tokenAuth'),
            logged_email: sessionStorage.getItem('email'),
        }
    },
    methods:{
        login:function(){
            this.logged_email = this.email;
            axios.post('/api/login',{
                'email': this.email,
                'password' : this.password
            }).then(response=>{
                console.log(response.data);
                this.logged = Object.values(response.data[0])[2].toString();
                sessionStorage.setItem('tokenAuth',Object.values(response.data[0])[2].toString());
                sessionStorage.setItem('id', response.data[1]);
                sessionStorage.setItem('email', this.email);
                axios.defaults.headers.common.Authorization = "Bearer " +sessionStorage.getItem('tokenAuth');
                //var token = sessionStorage.getItem('tokenAuth');
                //this.$bus.$emit('logged-in', {data: this.email});
                //this.$eventHub.$emit('logged-in', this.email);
            }).catch(error=>{
                console.log(error);
            })
        },
        logout: function(){
            this.logged = null;
            sessionStorage.clear();
        }
    }
}
</script>

<style>

</style>
