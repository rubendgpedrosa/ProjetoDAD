<template>
    <div class="container">
        <div><p>Total of Wallets: {{totalWallets}}</p></div>
        <div><p>Sum Wallets: {{sumValue}}</p></div>
        <div><p>High Transfer: {{highestTransferValue}}</p></div>
        <!--line-chart
            v-if="loaded"
            :chart-data="rawMovementsData" :chart-labels="labelsTransferTypes"
            :options="options"/-->
        <div class="small">
        <bar-chart
            v-if="loaded" chart-id="bar-wallet"
            :chart-data="transferTypes" :chart-labels="labelsTransferTypes" :options="options"/>
        </div><bar-chart
            v-if="loaded" chart-id="bar-wallet"
            :chart-data="types" :chart-labels="labelsTypes" :options="options"/>
    </div>
</template>

<script>
    import moment from 'moment'
    import BarChart from './BarChart.vue'
    import LineChart from './LineChart.vue'

    export default {
        name: 'BarChartContainer',
        components: { BarChart, LineChart },
        data: () => ({
            rawMovementsData: '',
            datacollection: null,
            loaded: false,
            chartdata: null,
            totalWallets: null,
            transferTypes: [],
            labelsTransferTypes: ['Cash','Bank Transfer','MB'],
            types:[],
            labelsTypes: ['Incomes','Expenses'],
            labelsMonth: [],
            movementsMonth: [],
            sumValue: '',
            highestTransferValue:'',
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }),
        mounted () {
            this.requestData()
        }, methods:{
            requestData() {
                axios.get('api/statistics')
                    .then(response=>{this.rawMovementsData = response.data.movements
                        this.transferTypes = [response.data.cTransfer, response.data.btTransfer, response.data.mbTransfer]
                        this.totalWallets = response.data.countWallets
                        this.sumValue = response.data.sumWallets
                        this.highestTransferValue = response.data.highestTransferValue
                        this.types = [response.data.countIncomeTransfer,response.data.countExpenseTransfer]
                        this.loaded = true})
                    .catch( err => {
                        this.loaded = false
                    })
            },
            groupDateByDate(){
                //this.fromatMonth()
            }
        }
    }
</script>
