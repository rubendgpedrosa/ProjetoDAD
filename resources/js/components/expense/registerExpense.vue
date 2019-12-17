<template>
    <div>
        <div class="jumbotron">
            <h1>Expense Registration</h1>
        </div>
        <form>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label for="inputType">Type</label>
                    <select required class="form-control custom-select" id="inputType" v-model="newExpense.type">
                        <option v-for="type in types" :value="type.value">
                            {{type.name}}
                        </option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputCategory">Category</label>
                    <select required class="form-control custom-select" id="inputCategory" v-model="newExpense.category_id">
                        <option v-for="category in categories" :value="category.id">
                            {{category.name}}
                        </option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputValue">Value</label>
                    <input required type="number" class="form-control" id="inputValue" v-model.lazy="newExpense.value">
                </div>
            </div>
            <div class="mb-3">
                <label for="inputDescription">Description</label>
                <textarea r equired class="form-control" id="inputDescription" v-model="newExpense.description"></textarea>
            </div>
            <div class="form-row" v-if="newExpense.type === 'e'">
                <div class="col">
                    <label for="inputTypePayment">Type of Payment</label>
                    <select required class="form-control custom-select" id="inputTypePayment" v-model="newExpense.type_payment">
                        <option v-for="type_payment in types_payment" :value="type_payment.value">
                            {{type_payment.name}}
                        </option>
                    </select>
                </div>
                <div class="col">
                    <label for="inputValue">IBAN</label>
                    <input :disabled="newExpense.type_payment !== 'bt'" :required="newExpense.type_payment === 'bt'"
                           type="text" class="form-control" id="inputIBAN" v-model.lazy="newExpense.iban">
                </div>
            </div>
            <div class="form-row" v-if="newExpense.type === 'e'">
                <div class="col">
                    <label for="inputMBEntityCode">MB Entity Code</label>
                    <input :disabled="newExpense.type_payment !== 'mb'" :required="newExpense.type_payment === 'mb'"
                           type="text" class="form-control" id="inputMBEntityCode" v-model.lazy="newExpense.mb_entity_code">
                </div>
                <div class="col">
                    <label for="inputMBPaymentReference">MB Payment Reference</label>
                    <input :disabled="newExpense.type_payment !== 'mb'" :required="newExpense.type_payment === 'mb'"
                           type="text" class="form-control" id="inputMBPaymentReference" v-model.lazy="newExpense.mb_payment_reference">
                </div>
            </div>
            <div class="mb-3" v-if="newExpense.type === 'i'">
                <label for="inputEmail">Destination Email</label>
                <input :disabled="newExpense.type !== 'i'" type="text" class="form-control" id="inputEmail" v-model.lazy="newExpense.email">
            </div>
            <div class="mb-3" v-if="newExpense.type === 'i'">
                <label for="inputSourceDescription">Source Description</label>
                <textarea :disabled="newExpense.type !== 'i'" type="text" class="form-control" id="inputSourceDescription" v-model.lazy="newExpense.source_description"/>
            </div>
            <button type="submit" class="btn btn-primary" @click="submitExpense">Submit Expense</button>
        </form>
    </div>
</template>

<script>
export default{
    data: function(){
        return{
            expenseClicked: false,
            newExpense: {
                type: '',
                value: '',
                category_id: '',
                description: '',
                type_payment: '',
                iban: '',
                mb_entity_code: '',
                mb_payment_reference: '',
                email: '',
                source_description: '',
                id: 12
            },
            categories: [{}],
            types: [{name: 'Payment to External Entity', value: 'e'}, {name: 'Transfer Movement', value: 'i'}],
            types_payment: [{name: 'Bank Transfer', value: 'bt'}, {name: 'MB Payment', value: 'mb'}]
        }
    },
    methods: {
        toggleEditMovement: function () {
            this.expenseClicked = this.expenseClicked === false;
            console.log(this.expenseClicked);
        },
        submitExpense: function(){
            axios.post('/api/movements', this.newExpense).then(response => this.$emit('expense-registered')).catch(error => console.log(error.message));
        },
        getCategories: function(){
            axios.get('/api/categories').then(response => this.categories = response.data.data).catch(error => console.log(error.message));
        }
    },
    mounted() {
        this.getCategories();
    }
}
</script>

<style scoped>
    /*fixes text area issues with padding on a parent div.
    .box {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        width: 100px;
        padding: 10px;
    }*/
</style>
