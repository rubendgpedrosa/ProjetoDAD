<script>
    export default {
        props:['users'],
        data: function (){
            return{
                currentUser: {}
            }
        },
        methods:{
            //TODO pagination
            deleteUser: function(event){
                axios.delete(`/api/users/${event.id}`, event.id).then(response => {this.$emit('user-deleted', event.id)}).catch(error => console.log(error.message));
            }
        }
    }
</script>
<template>
    <div>

    <table class="table table-hover table-borderless">
        <thead>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Type</th>
            <th>Actions</th>

        </tr>
        </thead>
        <tbody>
        <tr v-for="user in users" :key="user.id">
          <td ><img :src="`./storage/fotos/${user.photo}`" class="img-circle" style="max-width:70px;"> </td>
            <td>{{user.name}}</td>
            <td>{{user.email}}</td>
            <td v-if="user.type==='o'">Operator</td>
            <td v-if="user.type==='a'">Administrator</td>
            <td v-if="user.type==='u'">Platform User</td>
            <td>
                <a class="btn btn-sm btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
                <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
            </td>

        </tr>
        </tbody>
    </table>
    </div>
</template>


<style scoped>

</style>
