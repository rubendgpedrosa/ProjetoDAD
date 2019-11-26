<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>
        <div><button class="btn btn-primary" @click="addCategory">Add</button></div>
        <categoryAddEdit :title="titleSection" @cancel-category="cancelCategory" v-if="addingCategory"/>
        <categoriesList :categories="categories" @edit-category="editCategory(currentCategory)" ref="categoryListRef"/>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
        <categoryAddEdit :title="titleSection" :currentCategory="currentCategory" @cancel-category="cancelCategory" v-if="editingCategory"/>
    </div>
</template>
<script>
    import CategoryList from './categoryList'
    import CategoryAddEdit from './categoryAddEdit'

    export default {
        data: function () {
            return{title: 'List Categories',
                titleSection: '',
                editingCategory: false,
                addingCategory:false,
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
            },
            addCategory: function(){
                this.titleSection = "Add Category";
                this.currentCategory = null;
                this.addingCategory = true;
                this.editingCategory = false;
                this.showSuccess = false;
            },
            editCategory: function (category) {
                this.titleSection = "Edit Category";
                this.currentCategory = category;
                this.addingCategory = false;
                this.editingCategory = true;
                this.showSuccess = false;
            },
            cancelCategory: function () {
                this.showSuccess = false;
                if (this.editingCategory) {
                    this.editingCategory = false;
                }
                if (this.addingCategory) {
                    this.addingCategory = false;
                }
            }

        },
        components: {
            "categoriesList":CategoryList,
            "categoryAddEdit":CategoryAddEdit
        },
        mounted() {
            this.getCategories();
        }
    }
</script>
<styles scoped></styles>
