<template>
    <div>
        <table class="table table-hover table-borderless">
            <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="category in categories" :key="category.id" :class="{active: currentCategory === category}">
                <td>{{category.name}}</td>
                <td v-if="category.type=='i'">Income</td>
                <td v-if="category.type=='e'">Expense</td>
                <td>
                    <a class="btn btn-sm btn-primary" v-on:click.prevent="editCategory(category)">Edit</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props:['categories'],
        data: function (){
            return{
                currentCategory: {}
            }
        },
        methods: {
            editCategory(category){
                this.currentCategory = category;
                this.$emit('edit-category', category);
            }
        }
    }
</script>

<style scoped>

</style>
