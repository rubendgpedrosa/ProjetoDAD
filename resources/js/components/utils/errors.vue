<template>
    <div>
        <div class="alert alert-danger" v-show="errors !== 'tou' || ibanValidated === false" role="alert">
            <ul style="padding-left: 20px;">
                <div v-show="errors.name !== undefined">
                    <li>Name can only contain letters and spaces!</li>
                </div>
                <div v-show="errors.type_user !== undefined">
                    <li>Must select a type of account!</li>
                </div>
                <div v-show="errors.email !== undefined">
                    <li>Email address already in use!</li>
                </div>
                <div v-show="errors.password !== undefined">
                    <li>Passwords must match!</li>
                </div>
                <div v-show="errors.photo !== undefined">
                    <li>An administrator/operator must have a photo!</li>
                </div>
                <div v-show="errors.nif !== undefined">
                    <li>NIF must have 9 numbers!</li>
                </div>
                <div v-show="errors.nif !== undefined">
                    <li>NIF must have 9 numbers!</li>
                </div>
                <div v-show="errors.id !== undefined">
                    <li>User must be logged in!</li>
                </div>
                <div v-show="errors.type !== undefined">
                    <li>Must select the type for this registration!</li>
                </div>
                <div v-show="errors.category_id !== undefined">
                    <li>Must select a category for this registration!</li>
                </div>
                <div v-show="errors.value !== undefined">
                    <li>Amount transfered can't be lower than 0.01 and higher than 5000!</li>
                </div>
                <div v-show="errors.type_payment !== undefined">
                    <li>Must select a type of payment for this registration!</li>
                </div>
                <div v-show="ibanValidated !== undefined & ibanValidated === false">
                    <li>IBAN must have 2 capital letter followed by 23 Numbers!</li>
                </div>
                <div v-show="errors.email_to_transfer !== undefined">
                    <li>Insert the destination email for the expense!</li>
                </div>
                <div v-show="errors.mb_entity_code !== undefined">
                    <li>MB entity code must have 5 numbers!</li>
                </div>
                <div v-show="errors.mb_payment_reference !== undefined">
                    <li>MB payment reference must have 9 numbers!</li>
                </div>
                <div v-show="errors.email_login !== undefined">
                    <li>A valid email is required to login!</li>
                </div>
                <div v-show="errors.password_login !== undefined">
                    <li>A password with at least 3 digits is required to login!</li>
                </div>
            </ul>
        </div>
    </div>
</template>

<script>
    export default{
        props:['errors', 'ibanValidated'],
    }
</script>
