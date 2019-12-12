<template>
    <div>
        <div class="jumbotron">
            <h1 class="display-4">{{ title }}</h1>
            <p class="lead">{{ welcome_message }}</p>
            <hr class="my-4">
            <div>
                <p>Current number of wallets: {{ number_wallets }}</p>
                <button v-if="clickedButton === false && formSubmitted === false" @click="clickedButton = true" class="btn btn-primary" href="#/register" role="button">Register Now</button>
            </div>
            <register-user v-if="clickedButton && formSubmitted === false" v-on:cancel-registration="cancelRegistration" v-on:form-submitted="formSubmittion"></register-user>
            <div v-if="clickedButton && formSubmitted ">
                Thank you for joining us!
            </div>
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
                clickedButton: false,
                formSubmitted: false
            }
        },
        methods: {
            getNumberWallets: function(){
                axios.get('/api/wallets').then(response => (this.number_wallets = response.data)).catch(error=>console.log(error.nessage));
            },
            cancelRegistration: function(){
                this.clickedButton = false;
            },
            formSubmittion: function(){
                this.formSubmitted = true;
                this.getNumberWallets();
            }
        },
        mounted (){
            this.getNumberWallets();
        }
    }
</script>

<style scoped>

</style>
