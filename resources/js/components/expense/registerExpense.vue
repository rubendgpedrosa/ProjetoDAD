<template>
    <div>
        <div class="jumbotron">
            <h1>Expense Registration</h1>
            <hr class="my-4">
            <form v-if="expenseSubmitted === false">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="inputType">Type*</label>
                        <select class="form-control custom-select" id="inputType" v-model="newExpense.type" required>
                            <option v-for="type in types" :value="type.value">
                                {{type.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="inputCategory">Category*</label>
                        <select class="form-control custom-select" id="inputCategory" v-model="newExpense.category_id">
                            <option v-for="category in categories" :value="category.id">
                                {{category.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="inputValue">Amount*</label>
                        <input min="0.01" required type="number" step="0.01" class="form-control" id="inputValue" v-model.number="newExpense.value">
                        <small id="passwordNotMatch" class="form-text text-muted"><a>Amount must be between 0.01€ and 5000€.</a></small>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="inputDescription">Description</label>
                    <textarea class="form-control" id="inputDescription" v-model="newExpense.description"></textarea>
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
                        <input :disabled="newExpense.type_payment !== 'bt'" maxlength="25" :required="newExpense.type_payment === 'bt'"
                               type="text" class="form-control" id="inputIBAN" v-model.lazy="newExpense.iban">
                        <small id="ibanInvalid" v-show="!validateIBAN" class="form-text text-muted">IBAN must have 2 Capital letter followed by 23 Numbers.</small>
                    </div>
                </div>
                <div class="form-row" v-if="newExpense.type === 0">
                    <div class="col">
                        <label for="inputMBEntityCode">MB Entity Code</label>
                        <input :disabled="newExpense.type_payment !== 'mb'" maxlength="5" :required="newExpense.type_payment === 'mb'"
                               type="text" class="form-control" id="inputMBEntityCode" v-model.lazy="newExpense.mb_entity_code">
                        <small id="entitycodeInvalid" class="form-text text-muted">MB entity code must have 5 numbers.</small>
                    </div>
                    <div class="col">
                        <label for="inputMBPaymentReference">MB Payment Reference</label>
                        <input :disabled="newExpense.type_payment !== 'mb'" maxlength="9" :required="newExpense.type_payment === 'mb'"
                               type="text" class="form-control" id="inputMBPaymentReference" v-model.lazy="newExpense.mb_payment_reference">
                        <small id="paymentreferenceInvalid" class="form-text text-muted">MB payment reference must have 9 numbers.</small>
                    </div>
                </div>
                <div class="mb-3" v-if="newExpense.type === 1">
                    <label for="inputEmail">Destination Email</label>
                    <vue-bootstrap-typeahead :minMatchingChars="2" id="inputEmail" :disabled="newExpense.type !== 1" v-model="newExpense.email" :data="walletsEmailOnly"/>
                    <small id="emailInvalid" class="form-text text-muted" v-show="!walletsEmailOnly.includes(newExpense.email)">Invalid Email.</small>
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
    </div>
</template>

<script>
    import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';

    export default{
        data: function(){
            return{
                expenseClicked: false,
                newExpense: {
                    //TODO issue with submiting new expense. Some fields seem to be cleared for unknown reasons when sending a new request.
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
                    id: sessionStorage.getItem('id'),
                },
                walletsEmailArray: [{}],
                invalidEmail: false,
                walletsEmailOnly: [],
                categories: [{}],
                lettersIBAN: '',
                numbersIBAN: '',
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
                let self = this;
                if(this.newExpense.type === 0){
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
                axios.post('/api/movements', this.newExpense).then(function(response){ if(  response.status === 201) {
                        self.registeredExpense();
                    }
                }).catch(error => console.log(error.message));
            },
            getCategories: function(){
                axios.get('/api/categories').then(response => this.categories = response.data.data).catch(error => console.log(error.message));
            },
            walletsEmail: function(){
                axios.get('api/walletsEmail').then(response => {
                    this.walletsEmailArray = response.data;
                    response.data.forEach(element => {
                        this.walletsEmailOnly.push(element.email)
                    });
                });
            },
            disableButtonSubmit: function(){
                return (this.newExpense.type === "" || !this.walletsEmailOnly.includes(this.newExpense.email) || this.newExpense.category === "" || this.newExpense.value === ""|| this.newExpense.type === "" ||
                    (this.newExpense.type === 1? this.newExpense.email === "":(this.newExpense.type_payment === "bt"? this.newExpense.iban === "":
                        (this.newExpense.mb_entity_code === "" || this.newExpense.mb_payment_reference === ""))));
            },
            registeredExpense: function(){
                this.expenseSubmitted = true;
                this.$eventHub.$emit('registered-expense');
            }
        },
        components: {
            VueBootstrapTypeahead,
        },
        mounted() {
            this.getCategories();
            this.walletsEmail();
        },
        computed: {
            validateIBAN: function(){
                this.lettersIBAN = this.newExpense.iban.substring(0,2);
                this.numbersIBAN = this.newExpense.iban.substring(2,25);
                if(((/^[A-Z]*$/).test(this.lettersIBAN) && this.lettersIBAN.length === 2) && ((/^[0-9]+$/).test(this.numbersIBAN) && this.numbersIBAN.length === 23)){
                    return true;
                }else{
                    return false;
                }
            }
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
