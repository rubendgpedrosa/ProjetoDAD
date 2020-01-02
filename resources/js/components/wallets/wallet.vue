s<template>
    <div>
        <div>
            <div class="jumbotron">
                <h1>{{ title }}</h1>
                <hr class="my-4">
                <div class="form-row">
                    <div class="col">
                        <label>Wallet Email:</label>
                        {{ walletData.email }}
                    </div>
                    <div class="col">
                        <label>Current Balance:</label>
                        {{ walletData.balance }} €
                    </div>
                </div>
            </div>
        </div>
        <movement-list :walletid="walletData.id"></movement-list>
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
                hideWallet: false,
            }
        },
        computed:{
            walletData: function(){
                return this.$store.state.wallet;
            }
        }
    }

</script>

<style scoped>

</style>
