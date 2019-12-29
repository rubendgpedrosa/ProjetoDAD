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
    //Completed US7
    import MovementList from "../movement/movementList";

    export default{
        name: 'Wallet',
        components: {MovementList},
        data: function() {
            return {
                title: 'My Wallet',
                wallet: this.$store.state.wallet,
                hideWallet: false,
                walletid: this.$store.state.walletID,
            }
        },
        created() {
            //this.$bus.$on('payment-done', (email) => {this.getWallet(email.data)});
        },
        beforeDestroy() {
            //this.$eventHub.$off('logged-in');
        },
    }

</script>

<style scoped>

</style>
