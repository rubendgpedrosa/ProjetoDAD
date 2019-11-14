<template>
        <div>
            <div class="jumbotron">
                <h1>{{ title }}</h1>

            </div>
            <button type="button" class="btn btn-primary" v-on:click="addingUser=true">Adicionar Utilizador</button>
            <usersList v-if="addingUser===false" :users="users"/>
            <addUser :adding-user="addingUser" @cancel-edit="cancelEdit" v-if="addingUser===true"/>

            <div class="alert alert-success" v-if="showSuccess">
                <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
                <strong>{{ successMessage }}</strong>
            </div>
        </div>
</template>
<script>
    import AddUser from './addUser';
    import UserList from './userList';

    export default {
        data: function () {
            return{title: 'Users',
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
                addingUser: false,
                currentUser: null,
            users: []}
        }, methods: {
           cancelEdit:function()
            {
              //  console.log("YO");
                this.addingUser=false;
            },
            getUsers: function () {
                axios.get('api/users')
                    .then(response=>{this.users = response.data.data});
            }
        },
        components: {
            "usersList":UserList,
            "addUser":AddUser
        },
        mounted() {
            this.getUsers();
        }
    }
</script>
<style scoped>

</style>
