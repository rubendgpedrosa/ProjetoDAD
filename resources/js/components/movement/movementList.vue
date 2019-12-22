<template>
    <div>
        <div class="jumbotron">
            <h1>Movements</h1>
        </div>
        {{filteredMovements}}
        {{searchObject}}
        <div>
            <div class="form-row">
                <div class="col-md-2 mb-3">
                    <label>Wallet ID</label>
                    <input type="number" class="form-control" v-model="searchObject.id" placeholder="Insert ID">
                </div>
                <div class="col-md-2 mb-3">
                    <label>Type</label>
                    <select class="form-control custom-select" v-model="searchObject.type">
                        <option v-for="type in types" :value="type.value">
                            {{type.name}}
                        </option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label>Category</label>
                    <select class="form-control custom-select" placeholder="Category" v-model="searchObject.category">
                        <option v-for="category in categories" :value="category.id">
                            {{category.name}}
                        </option>
                    </select>
                </div>
                <div v-if="searchObject.type === 'e'" class="col-md-2 mb-3">
                    <label>Type of Payment</label>
                    <select required class="form-control custom-select" v-model="searchObject.type_payment">
                        <option v-for="type_payment in types_payment" :value="type_payment.value">
                            {{type_payment.name}}
                        </option>
                    </select>
                </div>
                <div v-if="searchObject.type === 'i'" class="col-md-2 mb-3">
                    <label>Destination Email</label>
                    <vue-bootstrap-typeahead  :minMatchingChars="1" v-model="searchObject.email" :data="walletsEmailArray"/>
                </div>
                <div class="col-md-2 mb-3">
                    <label>Date Interval</label>
                    <date-picker type="datetime" range v-model="searchObject.timeInterval" value-type="format" format="YYYY-MM-DD HH:mm:ss"></date-picker>
                </div>
                <div>
                    <button class="btn btn-danger glyphicon glyphicon-trash"></button>
                </div>
            </div>
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
                <tr v-for="movement in pagedMovements" :key="movement.id" @click="moreMovementInformation(movement)">
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
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';

    export default {
        components: {MovementInformation, JwPagination, DatePicker, VueBootstrapTypeahead},
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
                    searchObject:
                        {
                            timeInterval: null,
                            type: '',
                            category: '',
                            start: '',
                            email: ''
                        },
                    walletsEmailArray: [],
                    types: [{name: 'Expense Movement', value: 'e'}, {name: 'Income Credit', value: 'i'}],
                    types_payment: [{name: 'Bank Transfer', value: 'bt'}, {name: 'MB Payment', value: 'mb'}]
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
            walletsEmail: function(){
                axios.get('api/walletsEmail').then(response => {response.data.forEach(element => this.walletsEmailArray.push(element.email))});
            },
            moreMovementInformation: function(movement){
                this.movementInformationClicked = true;
                this.movementClicked = movement;
                this.movementClicked.type_string = this.typeToString(movement.type);
                this.movementClicked.category_string = this.categoryToString(movement.category_id);
            },
            changeInformationClicked:function(){
                this.movementInformationClicked = false;
            },
            onChangePage: function(pagedMovements){
                this.pagedMovements = pagedMovements;
            },
            filterMovements: function(){
                this.filteredMovements = this.movements.filter(movement=>movement.id === this.searchObject.id);
            }
        },
        mounted() {
            this.getMovements();
            this.getCategory();
            this.walletsEmail();
        },
        watch: {
            searchObject: function() {
                return this.movements.filter((movement) => {
                    movement.id === searchObject.id
                })
            }
        }
    }
</script>

<style scoped>

</style>
