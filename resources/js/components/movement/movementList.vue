<template>
    <div>
        <div>
            <div class="form-row">
                <div class="col">
                    <label>Movement ID</label>
                    <input type="text" class="form-control" v-model="searchObject.id">
                </div>
                <div class="col">
                    <label>Type</label>
                    <select class="form-control custom-select" type="search" v-model="searchObject.type">
                        <option v-for="type in types" :value="type.value">
                            {{type.name}}
                        </option>
                    </select>
                </div>
                <div class="col">
                    <label>Category</label>
                    <select class="form-control custom-select" placeholder="Category" v-model="searchObject.category">
                        <option v-for="category in this.$store.state.categories" :value="category.id">
                            {{category.name}}
                        </option>
                    </select>
                    <button class="btn bg-transparent" style="margin-left: -40px; z-index: 100;">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="col">
                    <label>Type of Payment</label>
                    <select required class="form-control custom-select" v-model="searchObject.type_payment">
                        <option v-for="type_payment in types_payment" :value="type_payment.value">
                            {{type_payment.name}}
                        </option>
                    </select>
                </div>
                <div class="col">
                    <label>Destination Email</label>
                    <vue-bootstrap-typeahead :minMatchingChars="1" v-model="searchObject.email" :data="walletsEmailOnly"/>
                </div>
                <div class="col">
                    <label>Date Interval</label>
                    <date-picker type="datetime" range v-model="searchObject.timeInterval" value-type="format" format="YYYY-MM-DD HH:mm:ss"></date-picker>
                </div>
            </div>
        </div>
        <div>
            <table class="table table-hover table-borderless" v-if="movementInformationClicked === false">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Start Balance</th>
                    <th>End Balance</th>
                    <th>Value Transfered</th>
                    <th>
                        <button style="padding: 0px;" type="button" class="btn btn-sm btn-outline-primary" @click="clearFilter">Clear Filters</button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="movement in pagedMovements" :key="movement.id">
                    <td>{{movement.date}}</td>
                    <td>{{typeToString(movement.type)}}</td>
                    <td>{{categoryToString(movement.category_id)}}</td>
                    <td>{{movement.start_balance}}</td>
                    <td>{{movement.end_balance}}</td>
                    <td>{{movement.value}}</td>
                    <td><button class="glyphicon glyphicon-search" @click="moreMovementInformation(movement)"></button></td>
                </tr>
                </tbody>
            </table>
            <movement-information v-if="movementInformationClicked" :categories="this.$store.state.categories" :movementClicked="movementClicked" v-on:movement-information-clicked="changeInformationClicked"></movement-information>
            <jw-pagination class="d-flex justify-content-center" v-show="getFilteredMovements.length > 12 && !movementInformationClicked"
                           :pageSize="12" :items="getFilteredMovements" @changePage="onChangePage"></jw-pagination>
        </div>
        <h3 class="text-center" v-if="movements.length === 0">No Records Found!</h3>
    </div>
</template>

<script>
    //Completed US8
    import MovementInformation from './movementInformation';
    import JwPagination from 'jw-vue-pagination';
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';

    export default {
        components: {MovementInformation, JwPagination, DatePicker, VueBootstrapTypeahead},
        data: function () {
                return {
                    movements: this.$store.state.movements,
                    walletID: this.$store.state.walletID,
                    pagedMovements: [{}],
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
                    searchObject:{
                        id: '',
                        timeInterval: '',
                        type: '',
                        type_payment: '',
                        category: '',
                        email: ''
                    },
                    walletsEmail: this.$store.state.walletsEmail,
                    walletsEmailOnly: this.$store.state.walletsEmailArray,
                    types: [{name: 'Expense Movement', value: 'e'}, {name: 'Income Credit', value: 'i'}],
                    types_payment: [{name: 'Bank Transfer', value: 'bt'}, {name: 'MB Payment', value: 'mb'}]
                }
        }, methods: {
            typeToString: function(type){
                return (type === "e")?"Expense Movement":"Income Credit";
            },
            categoryToString: function(categoryID){
                for (let i = 0; i < this.$store.state.categories.length; i++) {
                    if(this.$store.state.categories[i].id === categoryID){
                        return this.$store.state.categories[i].name;
                    }
                }
                return "N/A";
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
            clearFilter: function () {
                this.searchObject.id = '';
                this.searchObject.type = '';
                this.searchObject.category = '';
                this.searchObject.type_payment = '';
                this.searchObject.email = '';
                this.searchObject.timeInterval = '';
                this.searchObject.walletsEmail = '';
            }
        },
        computed: {
            getFilteredMovements: function() {
                let self = this;
                let stuff = this.movements;
                if(this.searchObject.id !== '')
                    stuff = stuff.filter(movement => movement.id == self.searchObject.id);
                if(this.searchObject.type !== '')
                    stuff = stuff.filter(movement => movement.type === self.searchObject.type);
                if(this.searchObject.category !== '')
                    stuff = stuff.filter(movement => movement.category_id === self.searchObject.category);
                if(this.searchObject.type_payment !== '')
                    stuff = stuff.filter(movement => movement.type_payment === self.searchObject.type_payment);
                if(this.searchObject.email !== ''){
                    var walletId = this.walletsEmail.filter(wallet => wallet.email === self.searchObject.email);
                    if(walletId[0] !== undefined){
                        stuff = stuff.filter(movement => {return movement.transfer_wallet_id == walletId[0].id});
                    }
                }
                if(this.searchObject.timeInterval[0] && this.searchObject.timeInterval[1]){
                    var startDate = new Date(self.searchObject.timeInterval[0]);
                    var endDate = new Date(self.searchObject.timeInterval[1]);
                    stuff = stuff.filter(function(movement){
                        var movementDate = new Date(movement.date);
                        return movementDate.getTime() >= startDate.getTime() && movementDate.getTime() <= endDate.getTime();
                    });
                }
                return stuff;
            }
        },
        sockets:{
            transfer_executed_server: function(){
                let headerData = {Accept: 'Application/json',Authorization: this.$store.state.token};
                axios.get(`api/movements/${this.$store.state.user.id}`, { headers: headerData})
                    .then(response=>{ this.$store.commit('setMovements', response.data.reverse());
                    this.movements = response.data.reverse()})
                    .catch( error => { console.log(error.message); });
            }
        }
    }
</script>

<style scoped>

</style>
