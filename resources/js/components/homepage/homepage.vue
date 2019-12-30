<template>
    <div>
        <div class="jumbotron">
            <h1 class="display-4">{{ title }}</h1>
            <p class="lead">{{ welcome_message }}</p>
            <hr class="my-4">
            <div v-if="clickedButton && formSubmitted ">
                <div class="alert alert-success" role="alert">
                    Thank you for joining us!
                </div>
            </div>
            <div>
                <p>Current number of wallets: {{ this.$store.state.number_wallets }}</p>
                <button v-if="clickedButton === false && formSubmitted === false" @click="clickedButton = true" class="btn btn-primary" href="#/register" role="button">Register Now</button>
            </div>
            <register-user v-if="clickedButton && formSubmitted === false" v-on:cancel-registration="cancelRegistration" v-on:form-submitted="formSubmittion"></register-user>
        </div>
    </div>
</template>

<script>
    //TODO (US4) - logout must be moved somewhere else and redirect to the homepage.
    //TODO (US6) - API done but I'm not sure how the component should be used. What does 'outside of the platform mean'.
    //TODO (US12) - Notify user when in platform or through email when not using the SPA when deposit is made (US6/9/10).
    //TODO (US13) - Update wallet/movement information when operator adds movement (US6) or user transfers (US9/10).
    //TODO (US14) - Statistic for the user about whatever information we find necessary. What we choose affects grading.
    //TODO (US17) - Statistics for the admins about whatever information we find necessary. What we choose affects grading.

    export default{
        data: function() {
            return{
                title: 'Homepage',
                welcome_message: 'Welcome to our simple, yet elegant E-Wallet App',
                clickedButton: false,
                formSubmitted: false
            }
        },
        methods: {
            cancelRegistration: function(){
                this.clickedButton = false;
            },
            formSubmittion: function(){
                this.formSubmitted = true;
            }
        },
    }
</script>

<style scoped>

</style>
