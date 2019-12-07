<template>
    <div>
        <div class="jumbotron">
            <h1 class="display-4">{{ title }}</h1>
            <p class="lead">{{ welcome_message }}</p>
            <hr class="my-4">
            <div v-if="clickedButton ==   false">
                <p>Current number of wallets: {{ number_wallets }}</p>
                <p><button @click="clickedButton = true" class="btn btn-primary btn-lg" href="#/register" role="button">Register Now</button></p>
            </div>
            <register-user v-if="clickedButton"></register-user>
        </div>
    </div>
</template>

<script>
    /*As a user (anonymous or authenticated user) I want to access the application's initial page
    with a welcome message and information about the total number of virtual wallets;*/

    export default{
        data: function() {
            return{
                title: 'Homepage',
                welcome_message: 'Welcome to our simple, yet elegant E-Wallet App',
                number_wallets: '',
                clickedButton: false
            }
        },
        methods: {
            getNumberWallets: function(){
                axios.get('/api/walletNumber').then(response => (this.number_wallets = response.data));
            }
        },
        mounted (){
            this.getNumberWallets();
        }
    }
</script>

<style scoped>

</style>
