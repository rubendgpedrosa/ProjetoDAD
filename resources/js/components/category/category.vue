<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>

        <categoriesList :categories="categories"/>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
    </div>
</template>
<script>
    import CategoryList from './categoryList'

    export default {
        data: function () {
            return{title: 'List Categories',
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
                currentCategory: null,
                categories: []}
        }, methods: {
            getCategories: function () {
                axios.get('api/categories')
                .then(response=>{this.categories = response.data.data});
            }
        },
        components: {
            "categoriesList":CategoryList
        },
        mounted() {
            this.getCategories();
        }
    }
</script>
<styles scoped></styles>
