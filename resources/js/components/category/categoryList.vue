<template>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="category in categories" :key="category.id">
            <td>{{category.name}}</td>
            <td>{{category.type}}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props:['categories'],
        data: function (){
            return{
                currentCategory: {}
            }
        }
    }
</script>

<style scoped>

</style>
