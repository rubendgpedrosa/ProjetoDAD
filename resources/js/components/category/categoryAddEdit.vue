<template>
    <div class="jumbotron">
        <h2>{{title}}</h2>
        <div class="form-group">
            <label for="inputName">Name</label>
            <input
                type="text" class="form-control" v-model="currentCategory.name"
                name="name" id="inputName"
                placeholder="Name" value="" />
        </div>
        <div class="form-group">
            <label for="type">Type:</label>
            <select class="form-control" id="type" name="type" >
                <option v-for="type in types" v-bind:value="type.type"> {{ type.type }} </option>
            </select>
        </div>

        <div class="form-group">
            <a class="btn btn-primary" v-on:click.prevent="save()">Save</a>
            <a class="btn btn-light" v-on:click.prevent="cancel()">Cancel</a>
        </div>

    </div>
</template>

<script>
    export default {
        props:{
            title:{type :String, required: true},
            currentCategory: {type: Object, required: false}
        },
        data: function () {
            return {
                types: [{type:"Income"}, {type:"Expense"}]
            }
        },
        methods:{
            save: function () {
                //this.$emit('save', this.category);
            },
            cancel: function () {
                this.$emit('cancel-category');
            }
        }
    }
</script>

<style scoped>

</style>
