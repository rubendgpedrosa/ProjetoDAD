<template>
    <div>
        <div>
            <h1 class="display-4">{{ title }}</h1>
            <hr class="my-4">
            <div class="form-row jumbotron" style="padding-top:0px; padding-left: 0px;padding-right: 0px;">
                <div class="col" >
                    <label>Amount on Platform:</label>
                    <input disabled v-model="sumWallets">
                </div>
                <div class="col">
                    <label>Number of Wallets:</label>
                    <input disabled v-model="totalWallets">
                </div>
                <div class="col">
                    <label>Average Amount per Wallet:</label>
                    <input disabled v-model="averagePerWallet">
                </div>
                <div class="col">
                    <label>Highest Transfer Amount:</label>
                    <input disabled v-model="highestTransferValue">
                </div>
                <div class="col">
                    <label>Number of Movements:</label>
                    <input disabled v-model="countMovements">
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
    import moment from 'moment'
    import BarChart from './BarChart.vue'
    import LineChart from './LineChart.vue'

    export default {
        name: 'BarChartContainer',
        props: ['rawData', 'transferTypes', 'types'],
        components: { BarChart },
        data: function() {
            return{
                title: 'Platform Statistics',
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
            totalWallets: function(){
                return this.$store.state.number_wallets;
            },
            sumWallets: function(){
                return this.$store.state.adminStatistics.sumWallets;
            },
            averagePerWallet: function(){
                return this.$store.state.adminStatistics.averagePerWallet;
            },
            highestTransferValue: function(){
                return this.$store.state.adminStatistics.highestTransferValue;
            },
            countMovements: function(){
                return this.$store.state.adminStatistics.countMovements;
            }
        }
    }
</script>
