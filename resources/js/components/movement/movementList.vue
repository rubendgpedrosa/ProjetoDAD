<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Type</th>
            <th>Category</th>
            <th>Date</th>
            <th>Value</th>
            <th>Start Balance</th>
            <th>End Balance</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="movement in movements" :key="movement.id">
            <td>{{movement.type}}</td>
            <td>{{movement.category}}</td>
            <td>{{movement.date}}</td>
            <td>{{movement.value}}</td>
            <td>{{movement.start_balance}}</td>
            <td>{{movement.end_balance}}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props:['wallet'],
        data: function () {
            movememts:[]
        }, methods: {
        getMovements: function () {
            axios.get('api/movements/{wallet}')
                .then(response=>{this.movements = response.data.data});
            }
        },
        mounted() {
            this.getMovements();
        }
    }
</script>

<style scoped>

</style>
