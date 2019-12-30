<template>
    <div class="container">
        <div><p>Total of Wallets: {{totalWallets}}</p></div>
        <bar-chart
            v-if="loaded" chart-id="bar-wallet"
            :chart-data="balanceEmail" :chart-labels="labelsEmail" :options="options"/>
    </div>
</template>

<script>
    import BarChart from './BarChart.vue'

    export default {
        name: 'BarChartContainer',
        components: { BarChart },
        data: () => ({
            rawData: '',
            loaded: false,
            chartdata: null,
            totalWallets: null,
            balanceEmail: [],
            labelsEmail: [],
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
                        this.totalWallets = response.data.length
                        this.loaded = true})
                    .catch( err => {
                        this.loaded = false
                    })

                this.labelsEmail = this.rawData.map(entry => entry.email)
                this.balanceEmail = this.rawData.map(entry => entry.balance)
            }
        }
    }
</script>
