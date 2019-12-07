s<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>
        <table class="table table-striped">
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
            </tbody>
        </table>
        <movement-list></movement-list>
    </div>
</template>

<script>
    //this.$route.params.id

    import MovementList from "../movement/movementList";

    export default{
        name: 'Wallet',
        components: {MovementList},
        data: function() {
            return {
                title: 'My Wallet',
                wallet: ''
            }
        },methods:{
            getWallet: function() {
                axios.get(`/api/wallet/${this.$route.params.id}`).then(response => (this.wallet = response.data));
            }
        },
        mounted () {
            this.getWallet();
        }
        //Websockets!!!
        /*,
        watch: {
            wallet: function () {
                this.getWallet();
            }
        }*/
    }

</script>

<style scoped>

</style>
