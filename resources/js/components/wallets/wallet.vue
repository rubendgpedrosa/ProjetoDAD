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
        sockets:{
            transfer_executed_server: function(){
                let headerData = {Accept: 'Application/json',Authorization: this.$store.state.token};
                axios.get(`/api/wallet/${this.$store.state.user.id}`, { headers: headerData})
                    .then(response => {this.$store.commit('setWallet', response.data); console.log(this.$store.state.wallet)})
                    .catch( error => { console.log(error.message);});
            }
        }
    }

</script>

<style scoped>

</style>
