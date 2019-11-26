s<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>
        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Virtual Wallet: {{ wallet.email }} </td>
                <td>Current Balance: {{ wallet.balance }} € </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    //this.$route.params.id
    export default{
        name: 'Wallet',
        data: function() {
            return {
                title: 'Wallet Information',
                wallet: ''
            }
        },methods:{
            getWallet: function() {
                axios.get(`/api/wallet/${this.$route.params.id}`).then(response => (this.wallet = response.data))
            }
        },
        mounted () {
            this.getWallet();
        }
    }

</script>

<style scoped>

</style>
