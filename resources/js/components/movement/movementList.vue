<template>
    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Start Balance</th>
                <th>End Balance</th>
                <th>Value Transfered</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="movement in movements" :key="movement.id">
                <td>{{movement.date}}</td>
                <td>{{typeToString(movement.type)}}</td>
                <td>{{categoryToString(movement.category_id)}}</td>
                <td>{{movement.start_balance}}</td>
                <td>{{movement.end_balance}}</td>
                <td>{{movement.value}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data: function () {
                return {
                    movements: {},
                    categories: [],
                }
        }, methods: {
            getMovements: function () {
                axios.get(`api/movements/${this.$route.params.id}`)
                .then(response=>{this.movements = response.data.data/*, console.log(this.movements)*/});
            },
            getCategory: function () {
                axios.get('api/categories/')
                .then(response=>{this.categories = response.data.data/*, console.log(response.data.data)*/});
            },
            typeToString: function(type){
                return (type === "i")?"Expense Movement":"Income Credit";
            },
            categoryToString: function(categoryID){
                for (let i = 0; i < this.categories.length; i++) {
                    if(this.categories[i].id === categoryID){
                        return this.categories[i].name;
                    }
                }
                return "N/A";
            }
        },
        mounted() {
            this.getMovements();
            this.getCategory();
        }
    }
</script>

<style scoped>

</style>
