s<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>
        <table class="table table-hover table-borderless">
            <thead>
            <tr>
                <th>Wallet Email</th>
                <th>Current Balance</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td> {{ wallet.email }} </td>
                <td> {{ wallet.balance }} € </td>
            </tr>
            <tr><td></td><td></td></tr>
            </tbody>
        </table>
        <movement-list :walletid="walletid"></movement-list>
    </div>
</template>

<script>
    import MovementList from "../movement/movementList";

    export default{
        name: 'Wallet',
        components: {MovementList},
        data: function() {
            return {
                title: 'My Wallet',
                wallet: '',
                walletid: sessionStorage.getItem('id'),
            }
        },
        created() {
            //this.$bus.$on('payment-done', (email) => {this.getWallet(email.data)});
        },
        beforeDestroy() {
            //this.$eventHub.$off('logged-in');
        },
        methods:{
            getWallet: function() {
                axios.get(`/api/wallet/${(this.walletid)}`)
                    .then(response => {this.wallet = response.data;});
            }
        },
        mounted () {
            this.getWallet();
        }
    }

</script>

<style scoped>

</style>
