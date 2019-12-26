s<template>
    <div>
        <div>
            <div class="jumbotron">
                <h1>{{ title }}</h1>
                <hr class="my-4">
                <div class="form-row">
                    <div class="col">
                        <label>Wallet Email:</label>
                        {{ wallet.email }}
                    </div>
                    <div class="col">
                        <label>Current Balance:</label>
                        {{ wallet.balance }} €
                    </div>
                </div>
            </div>
        </div>
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
                hideWallet: false,
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
