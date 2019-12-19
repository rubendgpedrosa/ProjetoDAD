<template>
    <div>
        <div class="jumbotron">
            <h1>Movements</h1>
        </div>
        <table class="table table-hover table-borderless" v-if="movementInformationClicked === false">
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
            <tr v-if="filteredMovements.length > 0" v-for="movement in pagedMovements" :key="movement.id" @click="moreMovementInformation(movement)">
                <td>{{movement.date}}</td>
                <td>{{typeToString(movement.type)}}</td>
                <td>{{categoryToString(movement.category_id)}}</td>
                <td>{{movement.start_balance}}</td>
                <td>{{movement.end_balance}}</td>
                <td>{{movement.value}}</td>
            </tr>
            </tbody>
        </table>
        <movement-information v-if="movementInformationClicked" :categories="categories" :movementClicked="movementClicked" v-on:movement-information-clicked="changeInformationClicked"></movement-information>
        <jw-pagination :items="movements" @changePage="onChangePage"></jw-pagination>
        <h3 class="text-center" v-if="movements.length === 0">No Records Found!</h3>
    </div>
</template>

<script>
    import MovementInformation from './movementInformation';
    import JwPagination from 'jw-vue-pagination';

    export default {
        components: {MovementInformation, JwPagination},
        data: function () {
                return {
                    movements: [{}],
                    pagedMovements: [{}],
                    categories: [],
                    movementInformationClicked: false,
                    movementClicked: {},
                    filteredMovements: [{}],
                    filterSettings: {
                        date: '',
                        type: '',
                        category: '',
                        start_balance: '',
                        value_transfered: '',
                    },
                }
        }, methods: {
            getMovements: function () {
                axios.get(`api/movements/${this.$route.params.id}`)
                .then(response=>{ this.movements = response.data, this.totalPages = this.movements.length });
            },
            getCategory: function () {
                axios.get('api/categories/')
                .then(response=>{this.categories = response.data.data/*, console.log(response.data.data)*/});
            },
            typeToString: function(type){
                return (type === "e")?"Expense Movement":"Income Credit";
            },
            categoryToString: function(categoryID){
                for (let i = 0; i < this.categories.length; i++) {
                    if(this.categories[i].id === categoryID){
                        return this.categories[i].name;
                    }
                }
                return "N/A";
            },
            moreMovementInformation: function(movement){
                this.movementInformationClicked = true;
                this.movementClicked = movement;
                this.movementClicked.type_string = this.typeToString(movement.type)
                this.movementClicked.category_string = this.categoryToString(movement.category_id);
            },
            changeInformationClicked:function(){
                this.movementInformationClicked = false;
            },
            onChangePage: function(pagedMovements){
                this.pagedMovements = pagedMovements;
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
