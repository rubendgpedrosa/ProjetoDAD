<template>
    <div class="container">
        <div><p>Total of Wallets: {{totalWallets}}</p></div>
        <div><p>Sum Wallets: {{sumValue}}</p></div>
        <div><p>High Transfer: {{highestTransferValue}}</p></div>
        <bar-chart
            v-if="loaded" chart-id="bar-wallet"
            :chart-data="transferTypes" :chart-labels="labelsTransferTypes" :options="options"/>
    </div>
</template>

<script>
    import BarChart from './BarChart.vue'

    export default {
        name: 'BarChartContainer',
        components: { BarChart },
        data: () => ({
            rawData: '',
            datacollection: null,
            loaded: false,
            chartdata: null,
            totalWallets: null,
            transferTypes: [],
            labelsTransferTypes: ['c','bt','mb'],
            sumValue: '',
            highestTransferValue:'',
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }),
        mounted () {
            this.requestData()
            /*this.loaded = false
            try {
                const { userlist } = await fetch('api/statistics')
                this.chartdata = userlist
                this.loaded = true
                /*
            } catch (e) {
                console.error(e)
            }*/
        }, methods:{
            requestData() {
                axios.get('api/statistics')
                    .then(response=>{this.rawData = response.data
                        this.transferTypes = [response.data.cTransfer, response.data.btTransfer, response.data.mbTransfer]
                        this.totalWallets = response.data.countWallets
                        this.sumValue = response.data.sumWallets
                        this.highestTransferValue = response.data.highestTransferValue
                        this.loaded = true})
                    .catch( err => {
                        this.loaded = false
                    })
            }
        }
    }
</script>
