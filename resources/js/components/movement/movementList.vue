<template>

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
