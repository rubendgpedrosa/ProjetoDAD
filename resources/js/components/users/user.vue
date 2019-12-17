<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>

        </div>
        <usersList v-on:user-deleted="updateList" :users="users"/>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
    </div>
</template>
<script>

    import UserList from './userList'
    export default {
        data: function () {
            return{title: 'Users',
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
                currentUser: null,
                users: []}
        }, methods: {
            getUsers: function () {
                axios.get('api/users')
                    .then(response=>{this.users = response.data.data});
            },
            updateList: function(id){
                this.users = this.users.filter(user => {
                    return user.id != id;
                });
            }
        },
        components: {
            "usersList":UserList
        },
        mounted() {
            this.getUsers();
        }
    }
</script>
<style scoped>
</style>
