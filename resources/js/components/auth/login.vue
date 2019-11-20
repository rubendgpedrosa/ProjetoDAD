<template>
    <div class="container">
        <div class = jumbotron>
            <h1>LOGIN</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email"  type="email" class="form-control" name="email"required autocomplete="email" autofocus v-model="email">


                                </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" value="123" type="password" class="form-control" name="password" required autocomplete="current-password" v-model="password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" v-on:click="login()">
                                        Login
                                    </button>
                                    <a class="btn btn-link" href="">Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data:function(){
        return{
            email:"admin1@mail.pt",
            password:"123"
        }
    },
    methods:{
        login:function(){
            console.log(
                'Email: '+email.value+"\npassword: "+password.value
            );
            axios.post('/api/login',{
                'email':email.value,
                'password' :password.value
            }).then(response=>{
               sessionStorage.setItem('tokenAuth',Object.values(response.data)[2].toString())
                axios.defaults.headers.common.Authorization = "Bearer " +sessionStorage.getItem('tokenAuth');
            }).catch(error=>{
                console.log(error)
            })


}

    }

}
</script>
<style>

</style>
