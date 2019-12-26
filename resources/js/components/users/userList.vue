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
            <th>Actions</th>

        </tr>
        </thead>
        <tbody>
        <tr v-for="user in pagedUsers" :key="user.id">
          <td ><img :src="`./storage/fotos/${user.photo}`" class="img-circle" style="max-width:70px;"> </td>
            <td>{{user.name}}</td>
            <td>{{user.email}}</td>
            <td v-if="user.type==='o'">Operator</td>
            <td v-if="user.type==='a'">Administrator</td>
            <td v-if="user.type==='u'">Platform User</td>
            <td>
                <a class="btn btn-sm btn-primary" v-on:click.prevent="deactivateUser(user)">Edit</a>
                <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
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
        components: {JwPagination, VueBootstrapTypeahead},
        props:['users'],
        data: function (){
            return{
                currentUser: {},
                pagedUsers: [{}],
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
            deleteUser: function(event){
                axios.delete(`/api/users/${event.id}`, event.id).then(response => {this.$emit('user-deleted', event.id)}).catch(error => console.log(error.message));
            },
            deactivateUser: function(){

            },
            clearFilter: function(){
              this.searchObject.type = '';
              this.searchObject.name = '';
              this.searchObject.status = '';
              this.searchObject.email = '';
            },
            onChangePage: function(pagedUsers){
                this.pagedUsers = pagedUsers;
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
        }
    }
</script>

<style scoped>

</style>
