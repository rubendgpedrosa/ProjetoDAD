<template>
    <div>
        <div class="jumbotron">
            <div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" v-bind:class="{'active': createAccounts === true}" @click="createAccountsClicked">Create Privileged Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" v-bind:class="{'active': statistics === true}" @click="statisticsClicked">Statistics</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <register-admin v-if="createAccounts === true && statistics === false"></register-admin>
                <chart-container v-if="statistics === true && createAccounts === false"></chart-container>
            </div>
        </div>

    </div>
</template>

<script>
    import registerAdmin from "../users/registerAdmin";
    import ChartContainer from "../statistics/ChartContainer";

    export default{
        components:{registerAdmin, ChartContainer},
        data: function(){
            return{
                createAccounts: true,
                statistics: false,
            }
        },
        methods:{
            createAccountsClicked: function(){
                this.statistics = false;
                this.createAccounts = true;
            },
            statisticsClicked: function(){
                this.createAccounts = false;
                this.statistics = true;
            }
        }
    }
</script>

<style scoped>

</style>
