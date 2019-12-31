<template>
    <div class="jumbotron">
        <div>
            <h1>{{ title }}</h1>
        </div>
        <div><button class="btn btn-primary" @click="addCategory">Add</button></div>
        <categoryAddEdit :title="'Add Category'" :currentCategory="currentCategory" @save-category="saveCategory" @cancel-category="cancelCategory" v-if="addingCategory"/>
        <categoriesList :categories="this.$store.state.categories" @edit-category="editCategory" ref="categoryListRef"/>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
        <categoryAddEdit :title="'Edit Category'" :currentCategory="currentCategory" @save-category="saveCategory" @cancel-category="cancelCategory" v-if="editingCategory"/>
    </div>
</template>
<script>
    import CategoryList from './categoryList'
    import CategoryAddEdit from './categoryAddEdit'
    export default {
        data: function () {
            return{title: 'List Categories',
                editingCategory: false,
                addingCategory:false,
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
                currentCategory: null,
            }
        }, methods: {
            addCategory: function(){
                this.currentCategory = {};
                this.addingCategory = true;
                this.editingCategory = false;
                this.showSuccess = false;
            },
            editCategory: function (category) {
                this.currentCategory = category;
                this.addingCategory = false;
                this.editingCategory = true;
                this.showSuccess = false;
            },
            saveCategory: function(){
                //console.dir(this.currentCategory);
                if (this.editingCategory) {
                    this.$refs.categoryListRef.currentCategory = null;
                    axios.put('api/categories/'+this.currentCategory.id, this.currentCategory)
                        .then(response=> {
                           this.showSuccess =true;
                           this.successMessage = 'Category Saved';
                           Object.assign(this.currentCategory, response.data.data);
                        });
                }
                if (this.addingCategory) {
                    axios.post('api/categories/'+this.currentCategory.id, this.currentCategory)
                        .then(response=> {
                            this.showSuccess =true;
                            this.successMessage = 'Category Created';
                            Object.assign(this.currentCategory, response.data.data);
                        });
                }

                this.currentCategory = null;
                this.editingCategory=false;
                this.addingCategory=false;
            },
            cancelCategory: function () {
                this.showSuccess = false;
                if (this.editingCategory) {
                    this.editingCategory = false;
                    this.$refs.userListRef.currentCategory=null;
                    axios.get('api/categories/'+this.currentCategory.id)
                        .then(response=>{
                            console.dir (this.currentCategory);
                            // Copies response.data.data properties to this.currentUser
                            // without changing this.currentUser reference
                            Object.assign(this.currentCategory, response.data.data);
                            console.dir (this.currentCategory);
                        });
                }

                if (this.addingCategory) {
                    this.addingCategory = false;
                }

                this.currentCategory = null;
            }

        },
        components: {
            "categoriesList":CategoryList,
            "categoryAddEdit":CategoryAddEdit
        }
    }
</script>
<styles scoped></styles>
