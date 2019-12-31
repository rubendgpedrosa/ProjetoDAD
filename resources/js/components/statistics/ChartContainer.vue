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
            <bar-chart
                chart-id="bar-wallet"
                :chart-data="transferTypes" :chart-labels="labelsTransferTypes" :options="options"/>
        </div>
    </div>
</template>

<script>
    import BarChart from './BarChart.vue';

    /*
    this.rawData = response.data
    this.transferTypes = [response.data.cTransfer, response.data.btTransfer, response.data.mbTransfer]
    this.totalWallets = response.data.countWallets
    this.sumValue = response.data.sumWallets
    this.highestTransferValue = response.data.highestTransferValue
    this.loaded = true
    */

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
                transferTypes: [],
                labelsTransferTypes: ['c','bt','mb'],
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        }
    }
</script>
