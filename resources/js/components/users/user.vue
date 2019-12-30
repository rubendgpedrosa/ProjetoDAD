<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>

        </div>
        <usersList v-on:user-deleted="updateList" v-on:refresh-data="updateDataUsers" :users="users"/>

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
            return{
                title: 'Users',
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
                currentUser: null,
                users: this.$store.state.users,
            }
        }, methods: {
            updateDataUsers: function(user){
                if(user.active === 0){
                    user.active = 1;
                }else{
                    user.active = 0;
                }
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
    }
</script>
<style scoped>
</style>
