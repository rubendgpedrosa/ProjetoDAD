<template>
    <div>
        <div class="jumbotron">
            <h1>Expense Registration</h1>
        </div>
        <form v-if="expenseSubmitted === false">
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label for="inputType">Type</label>
                    <select class="form-control custom-select" id="inputType" v-model="newExpense.type" required>
                        <option v-for="type in types" :value="type.value">
                            {{type.name}}
                        </option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputCategory">Category</label>
                    <select class="form-control custom-select" id="inputCategory" v-model="newExpense.category_id">
                        <option v-for="category in categories" :value="category.id">
                            {{category.name}}
                        </option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputValue">Amount</label>
                    <input required type="number" class="form-control" id="inputValue" v-model.number="newExpense.value">
                    <small min="0.01" required v-show="newExpense.value !== '' & newExpense.value > 5000.0 || newExpense.value < 0.01" id="passwordNotMatch" style="color:red;" class="form-text text-muted"><a style="color:red">Amount must be between 0.01€ and 5000€.</a></small>
                </div>
            </div>
            <div class="mb-3">
                <label for="inputDescription">Description</label>
                <textarea class="form-control" id="inputDescription" v-model="newExpense.description" required></textarea>
            </div>
            <div class="form-row" v-if="newExpense.type === 0">
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
            <div class="form-row" v-if="newExpense.type === 0">
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
            <div class="mb-3" v-if="newExpense.type === 1">
                <label for="inputEmail">Destination Email</label>
                <vue-bootstrap-typeahead :minMatchingChars="1" id="inputEmail" :disabled="newExpense.type !== 1" v-model="newExpense.email" :data="walletsEmailArray"/>
            </div>
            <div class="mb-3" v-if="newExpense.type === 1">
                <label for="inputSourceDescription">Source Description</label>
                <textarea :disabled="newExpense.type !== 1" type="text" class="form-control" id="inputSourceDescription" v-model.lazy="newExpense.source_description"/>
            </div>
            <button type="submit" :disabled="disableButtonSubmit()" class="btn btn-primary" @click="submitExpense">Submit Expense</button>
        </form>
        <div v-if="expenseSubmitted">
            <button type="submit" class="btn btn-primary" @click="expenseSubmitted = false">Submit New Expense</button>
        </div>
    </div>
</template>

<script>
    import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';

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
                walletsEmailArray: [],
                categories: [{}],
                expenseSubmitted: false,
                types: [{name: 'Payment to External Entity', value: 0}, {name: 'Transfer Movement', value: 1}],
                types_payment: [{name: 'Bank Transfer', value: 'bt'}, {name: 'MB Payment', value: 'mb'}]
            }
        },
        methods: {
            toggleEditMovement: function () {
                this.expenseClicked = this.expenseClicked === false;
            },
            submitExpense: function(){
                if(this.newExpense.type === "e"){
                    this.newExpense.email = "";
                    if(this.newExpense.type_payment === "bt"){
                        this.newExpense.mb_entity_code = "";
                        this.newExpense.mb_payment_reference = "";
                    }else{
                        this.newExpense.iban = "";
                    }
                }else{
                    this.newExpense.mb_entity_code = "";
                    this.newExpense.mb_payment_reference = "";
                    this.newExpense.iban = "";
                }
                axios.post('/api/movements', this.newExpense).then(function(response){
                    if(response.status === 200){
                        this.expenseSubmitted = true;
                        this.$emit('expense-registered');
                    }
                }).catch(error => console.log(error.message));
            },
            getCategories: function(){
                axios.get('/api/categories').then(response => this.categories = response.data.data).catch(error => console.log(error.message));
            },
            walletsEmail: function(){
                axios.get('api/walletsEmail').then(response => {response.data.forEach(element => this.walletsEmailArray.push(element.email))});
            },
            disableButtonSubmit: function(){
                return (this.newExpense.type === "" || this.newExpense.category === "" || this.newExpense.value === ""|| this.newExpense.type === "" ||
                    (this.newExpense.type === 1? this.newExpense.email === "":(this.newExpense.type_payment === "bt"? this.newExpense.iban === "":
                        (this.newExpense.mb_entity_code === "" || this.newExpense.mb_payment_reference === ""))));
            }
        },
        components: {
            VueBootstrapTypeahead,
        },
        mounted() {
            this.getCategories();
            this.walletsEmail();
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
