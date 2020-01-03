<template>
    <div>
        <div class="jumbotron">
            <div v-if="user.type === 'u'">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" v-bind:class="{'active': updateProfile === true}" @click="editProfileClicked">Create Privileged Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" v-bind:class="{'active': statistics === true}" @click="statisticsClicked">Statistics</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <user-profile v-bind:altered-user="user" v-if="updateProfile === true && statistics === false" />
                <chart-container-users v-if="statistics === true && updateProfile === false" />
            </div>
        </div>
    </div>
</template>

<script>
    import ChartContainerUsers from "../statistics/ChartContainerUsers";
    import userProfile from '../profile/userProfile';

    export default{
        components:{ChartContainerUsers, userProfile },
        data: function(){
            return{
                updateProfile: true,
                statistics: false,
            }
        },
        methods:{
            editProfileClicked: function(){
                this.statistics = false;
                this.updateProfile = true;
            },
            statisticsClicked: function(){
                this.updateProfile = false;
                this.statistics = true;
            }
        },
        computed:{
            user: function(){
                return this.$store.state.user;
            }
        }
    }
</script>

<style scoped>

</style>
