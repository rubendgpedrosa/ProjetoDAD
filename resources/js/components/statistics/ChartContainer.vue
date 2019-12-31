<template>
    <div>
        <div>
            <h1 class="display-4">{{ title }}</h1>
            <hr class="my-4">
            <div class="form-row jumbotron" style="padding-top:0px; padding-left: 0px;padding-right: 0px;">
                <div class="col" >
                    <label>Amount on Platform:</label>
                    <input class="col" disabled v-model="this.$store.state.adminStatistics.sumWallets">
                </div>
                <div class="col">
                    <label>Number of Wallets:</label>
                    <input class="col" disabled v-model="this.$store.state.number_wallets">
                </div>
                <div class="col">
                    <label>Average Amount per Wallet:</label>
                    <input class="col" disabled v-model="this.$store.state.adminStatistics.averagePerWallet">
                </div>
                <div class="col">
                    <label>Highest Transfer Amount:</label>
                    <input class="col" disabled v-model="this.$store.state.adminStatistics.highestTransferValue">
                </div>
                <div class="col">
                    <label>Number of Movements:</label>
                    <input class="col" disabled v-model="this.$store.state.adminStatistics.countMovements">
                </div>
            </div>
            <h3>Transfer Types</h3>
            <bar-chart
                chart-id="bar-wallet"
                :chart-data="transferTypes" :chart-labels="labelsTransferTypes" :options="options"/>
            <h3>Types</h3>
            <bar-chart chart-id="bar-types"
                :chart-data="types" :chart-labels="labelsTypes" :options="options"/>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import BarChart from './BarChart.vue'
    import LineChart from './LineChart.vue'

    export default {
        name: 'BarChartContainer',
        components: { BarChart },
        data: function() {
            return{
                title: 'E-Wallet Statistics',
                rawData: this.$store.state.adminStatistics,
                datacollection: null,
                loaded: false,
                chartdata: null,
                totalWallets: null,
                transferTypes: [this.$store.state.adminStatistics.cTransfer, this.$store.state.adminStatistics.btTransfer, this.$store.state.adminStatistics.mbTransfer],
                labelsTransferTypes: ['Cash','Bank Transfer','MB'],
                types:[this.$store.state.adminStatistics.countIncomeTransfer,this.$store.state.adminStatistics.countExpenseTransfer],
                labelsTypes: ['Incomes','Expenses'],
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        }
    }
</script>
