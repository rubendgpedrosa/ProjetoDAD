<template>
    <div>
        <div class="jumbotron">
            <h1>Income Registration</h1>
            <hr class="my-4">
            <errors :errors="validationErrors" :ibanValidated="ibanValidated"></errors>
            <form v-if="incomeSubmitted === false">
                <div class="form-row">
                    <div class="col">
                        <label>Email*</label>
                        <vue-bootstrap-typeahead :minMatchingChars="2" v-model="newIncome.email_income" :data="walletsEmailOnly"/>
                    </div>
                    <div class="col">
                        <label>Amount*</label>
                        <input min="0.01" required type="number" step="0.01" class="form-control" id="inputValue" v-model.number="newIncome.value_income">
                    </div>
                    <div class="col">
                        <label>Type of Payment</label>
                        <select required class="form-control custom-select" v-model="newIncome.type_payment_income">
                            <option v-for="type_payment in types_payment" :value="type_payment.value">
                                {{type_payment.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <label>IBAN</label>
                        <input :disabled="newIncome.type_payment_income !== 'bt'" maxlength="25" :required="newIncome.type_payment_income === 'bt'"
                               type="text" class="form-control" id="inputIBAN" v-model.lazy="newIncome.IBAN_income">
                    </div>
                </div>
                <div>
                    <div>
                        <label>Source Description</label>
                        <textarea type="text" class="form-control"v-model.lazy="newIncome.source_description_income"/>
                    </div>
                </div>
                <div style="padding-top: 10px;">
                    <button type="submit" :disabled="disableButtonSubmit()" class="btn btn-primary" @click="submitIncome">Submit Income</button>
                </div>
            </form>
            <div v-if="incomeSubmitted">
                <div class="alert alert-success" role="alert">
                    New income registered successfully!
                </div>
                <button type="submit" class="btn btn-primary" @click="incomeSubmitted = false">Submit New Income</button>
            </div>
        </div>
    </div>
</template>

<script>
    /*
    As a platform operator I want to be able to register an income (credit) movement from the
    outside of the platform. The registration requires the e-mail of the virtual wallet that will
    receive the money; the value to credit on the wallet (from 0,01€ up to 5000,00€); the type
    of payment (cash or bank transfer); the source description and the IBAN (2 capital letters
    followed by 23 digits) - when the type of payment is a bank transfer.
    The current balance of the virtual wallet that receives the money must be updated
    automatically, as well as the start balance and end balance of the movement itself.
    */
    import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
    import errors from '../utils/errors.vue';

    export default{
        data: function(){
            return{
                newIncome:{
                    email_income: '',
                    value_income: '',
                    type_payment_income: '',
                    source_description_income: '',
                    IBAN_income: '',
                },
                walletsEmailOnly: this.$store.state.walletsEmailArray,
                types_payment: [{name: 'Bank Transfer', value: 'bt'}, {name: 'MB Payment', value: 'mb'}],
                incomeSubmitted: false,
                ibanValidated: '',
                validationErrors: 'tou'
            }
        },
        methods:{
            submitIncome: function(){
                let self = this;
                if(this.newIncome.type_payment_income === "bt"){
                    this.lettersIBAN = this.newIncome.IBAN_income.substring(0,2);
                    this.numbersIBAN = this.newIncome.IBAN_income.substring(2,25);
                    if(((/^[A-Z]*$/).test(this.lettersIBAN) && this.lettersIBAN.length === 2) && ((/^[0-9]+$/).test(this.numbersIBAN) && this.numbersIBAN.length === 23)){
                        this.ibanValidated = true;
                    }else{
                        this.ibanValidated = false;
                    }
                }else{
                    this.newIncome.IBAN_income = "";
                }
                axios.put('api/wallets', this.newIncome,{headers: {Accept: 'Application/json', Authorization: this.$store.state.token}})
                .then(function(response){ if(  response.status === 201) {
                        self.registeredIncome();
                        self.$toasted.show('Income successfully submitted!', { type: 'success' });
                        self.$socket.emit('wallet_movements', self.newIncome.email_income);
                    }
                }).catch(error => {
                if (error.response.status === 422){
                    this.$toasted.show('Error occurred submitting expense!', { type: 'error' });
                    this.validationErrors = error.response.data.errors;
                }});
            },
            disableButtonSubmit: function(){
                return (this.newIncome.email_income === "" || this.newIncome.value_income === "" || this.newIncome.type_payment_income === ""||
                    this.newIncome.source_description_income === "" || (this.newIncome.type_payment_income === "bt"? this.newIncome.IBAN_income === "":false));
            },
            registeredIncome: function(){
                this.incomeSubmitted = true;
            }
        },
        components: {
            VueBootstrapTypeahead, errors
        }
    }
</script>

<style scoped>

</style>
