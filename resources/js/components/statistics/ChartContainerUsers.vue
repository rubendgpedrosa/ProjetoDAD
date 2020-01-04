<template>
    <div>
        <div>
            <h1 class="display-4">{{ title }}</h1>
            <hr class="my-4">
            <div class="form-row jumbotron" style="padding-top:0px; padding-left: 0px;padding-right: 0px;">
                <div class="col" >
                    <label>Highest Held Amount:</label>
                    <input disabled v-model="userStatistics.highestHeldAmount">
                </div>
                <div class="col">
                    <label>Number of Transactions:</label>
                    <input disabled v-model="userStatistics.numberOfTransactions">
                </div>
                <div class="col">
                    <label>Highest Transaction:</label>
                    <input disabled v-model="userStatistics.highestTransactionAmount">
                </div>
                <div class="col">
                    <label>Nº Wallets Interacted:</label>
                    <input disabled v-model="userStatistics.numberWalletsInteractedWith">
                </div>
                <div class="col">
                    <label>Wallet Most Interacted:</label>
                    <input disabled v-model="userStatistics.walletMostInteractedWith === null? 'None':userStatistics.walletMostInteractedWith">
                </div>
            </div>
            <h3 style="padding-bottom: 20px;">Transfer Types</h3>
            <bar-chart
                chart-id="bar-wallet"
                :chart-data="transferTypes" :chart-labels="labelsTransferTypes" :options="options"/>
            <h3 style="padding-bottom: 20px;">Types</h3>
            <bar-chart chart-id="bar-types"
                       :chart-data="types" :chart-labels="labelsTypes" :options="options"/>
        </div>
    </div>
</template>

<script>
    import BarChart from './BarChart.vue'

    export default {
        name: 'BarChartContainer',
        components: { BarChart },
        data: function() {
            return{
                title: 'My E-Wallet Statistics',
                datacollection: null,
                loaded: false,
                chartdata: null,
                labelsTransferTypes: ['Cash','Bank Transfer','MB'],
                labelsTypes: ['Incomes','Expenses'],
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        },
        computed:{
            userStatistics: function() {
                return this.$store.state.userStatistics;
            },
            transferTypes: function(){
                return [this.$store.state.userStatistics.cTransfer,
                    this.$store.state.userStatistics.btTransfer,
                    this.$store.state.userStatistics.mbTransfer];
            },
            types: function(){
                return [this.$store.state.userStatistics.numberOfIncomes,
                    this.$store.state.userStatistics.numberOfExpenses];
            }
        }
    }
</script>
