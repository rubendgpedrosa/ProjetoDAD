<template>
    <div>
        <div class="jumbotron">
            <h1 class="display-4">{{ title }}</h1>
            <p class="lead">{{ welcome_message }}</p>
            <hr class="my-4">
            <div v-if="this.$store.state.errorNotLogged === true && not_logged === false && clickedButton === false && formSubmitted === false" class="alert alert-danger">
                You must be logged in to access that!
            </div>
            <div v-if="clickedButton && formSubmitted ">
                <div class="alert alert-success" role="alert">
                    Thank you for joining us!
                </div>
            </div>
            <div>
                <p v-if="not_logged === false && clickedButton === false && formSubmitted === false">Current number of wallets: {{ this.$store.state.number_wallets }}</p>
                <button v-if="not_logged === false && clickedButton === false && formSubmitted === false"
                        @click="clickRegister" class="btn btn-primary" href="#/register" role="button">Register Now</button>
                <button v-if="not_logged === false && clickedButton === false && formSubmitted === false"
                        @click="clickLogin" class="btn btn-primary" href="#/register" role="button">Already an User?</button>
            </div>
            <register-user v-if="clickedButton && formSubmitted === false" v-on:cancel-registration="cancelRegistration" v-on:form-submitted="formSubmittion"></register-user>
            <login v-if="not_logged === true" v-on:cancel-login="not_logged = false"></login>
        </div>
    </div>
</template>

<script>
    //TODO (US12) - (DONE)Notify user when in platform or (TODO)through email when not using the SPA when deposit is made (US6/9/10).
    //TODO (US14) - Statistic for the user about whatever information we find necessary. What we choose affects grading.
    //TODO (US17) - Statistics for the admins about whatever information we find necessary. What we choose affects grading.

    import Login from '../auth/login.vue';

    export default{
        components: {Login},
        data: function() {
            return{
                title: 'Homepage',
                welcome_message: 'Welcome to our simple, yet elegant E-Wallet App',
                clickedButton: false,
                formSubmitted: false,
                not_logged: this.$store.state.logged_in,
            }
        },
        methods: {
            cancelRegistration: function(){
                this.clickedButton = false;
            },
            clickRegister: function(){
                this.clickedButton = true;
                this.$store.commit('resetErrorLogged');
            },
            clickLogin: function(){
                this.not_logged = true;
                this.$store.commit('resetErrorLogged');
            },
            formSubmittion: function(){
                this.formSubmitted = true;
            }
        },
    }
</script>

<style scoped>

</style>
