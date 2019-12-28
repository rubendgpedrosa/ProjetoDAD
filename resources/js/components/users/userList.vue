<template>
    <div>
        <div>
            <button style="padding: 0px;" type="button" class="btn btn-sm btn-outline-primary" @click="clearFilter">Clear Filters</button>
            <div class="form-row">
                <div class="col">
                    <label>Type</label>
                    <select class="form-control custom-select" type="search" v-model="searchObject.type">
                        <option v-for="type in types" :value="type.value">
                            {{type.name}}
                        </option>
                    </select>
                </div>
                <div class="col">
                    <label>Name</label>
                    <input type="text" class="form-control" v-model="searchObject.name">
                </div>
                <div class="col">
                    <label>Email</label>
                    <input type="text" class="form-control" v-model="searchObject.email">
                </div>
                <div class="col">
                    <label>Status</label>
                    <select class="form-control custom-select" type="search" v-model="searchObject.status">
                        <option v-for="status in statusOptions" :value="status.value">
                            {{status.name}}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    <table class="table table-hover table-borderless">
        <thead>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Type</th>
            <th>Status</th>
            <th>Wallet Status</th>
            <th></th>

        </tr>
        </thead>
        <tbody>
        <tr v-for="user in pagedUsers" :key="user.id">
          <td ><img :src="`./storage/fotos/${user.photo}`" class="img-circle" style="max-width:50px;"> </td>
            <td>{{user.name}}</td>
            <td>{{user.email}}</td>
            <td>{{user.type ==='o'? 'Operator':(user.type==='a'?'Administrator':'Platform User')}}</td>
            <td>{{user.active === 1? 'Active': 'Not Active'}}</td>
            <td>{{user.empty_wallet === false? 'Wallet Not Empty':(user.empty_wallet === true? 'Wallet Is Empty':'No Wallet')}}</td>
            <td>
                <a v-if="user.empty_wallet !== undefined" class="btn btn-sm" :disabled="user.empty_wallet !== true"
                   v-bind:class="{'btn-outline-success': user.active === 0, 'btn-outline-danger': user.active === 1}"
                   v-on:click.prevent="toggleUserActivation(user)">{{buttonText(user)}}</a>
                <a v-if="user.type !== 'u'" class="btn btn-sm btn--sm btn-outline-danger" v-on:click.prevent="deleteAccount(user)">Delete</a>
            </td>
        </tr>
        </tbody>
    </table>
        <jw-pagination class="d-flex justify-content-center" :items="getFilteredMovements" @changePage="onChangePage"></jw-pagination>
    </div>
</template>

<script>
    import JwPagination from 'jw-vue-pagination';
    import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
    import MovementInformation from "../movement/movementInformation";

    export default {
        /*US16. Also, as an administrator I want to remove any operator or administrator account (except
        my own account) and deactivate or reactivate platform users – an account can only be
        deactivated if it has a virtual wallet with a balance value of zero.*/
        components: {JwPagination, VueBootstrapTypeahead},
        props:['users'],
        data: function (){
            return{
                currentUser: {},
                pagedUsers: [{}],
                walletsArray: [{}],
                searchObject:
                    {
                        type: '',
                        name: '',
                        status: '',
                        email: ''
                    },
                types: [{name: 'Platform User', value: 'u'}, {name: 'Administrator', value: 'a'}, {name: 'Operator', value: 'o'}],
                statusOptions: [{name: 'Not Active', value: '0'}, {name: 'Active', value: '1'}],
            }
        },
        methods:{
            deleteAccount: function(event){
                axios.delete(`/api/users/${event.id}`, event.id)
                    .then(response => {this.$emit('user-deleted', event.id)})
                    .catch(error => console.log(error.message));
            },
            toggleUserActivation: function(user){
                axios.put(`api/users/${user.id}`, {type_update: 'activity', email: user.email})
                    .then(response => {this.$emit('refresh-data', user)})
                    .catch(error => console.log(error.message));
            },
            clearFilter: function(){
              this.searchObject.type = '';
              this.searchObject.name = '';
              this.searchObject.status = '';
              this.searchObject.email = '';
            },
            onChangePage: function(pagedUsers){
                this.pagedUsers = pagedUsers;
            },
            buttonText: function(user){
                if(user.active === 0){
                    return 'Enable';
                }else{
                    if(user.empty_wallet === true){
                        return 'Disable';
                    }else{
                        return "Can't Disable";
                    }
                }
            }
        },
        computed: {
            getFilteredMovements: function() {
                let self = this;
                let stuff = this.users;
                if(this.searchObject.name !== '')
                    stuff = stuff.filter(user => user.name.includes(self.searchObject.name));
                if(this.searchObject.type !== '')
                    stuff = stuff.filter(user => user.type === self.searchObject.type);
                if(this.searchObject.status !== '')
                    stuff = stuff.filter(user => user.active == self.searchObject.status);
                if(this.searchObject.email !== ''){
                    stuff = stuff.filter(user => user.email.includes(self.searchObject.email));
                }
                return stuff;
            }
        },
        mounted(){
        }
    }
</script>

<style scoped>

</style>
