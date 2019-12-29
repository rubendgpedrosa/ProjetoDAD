<template>
    <div>
        <div class="alert alert-danger" v-show="errors !== 'tou'" role="alert">
            <ul style="padding-left: 20px;">
                <div v-show="errors.name !== undefined">
                    <li>Name can only contain letters and spaces!</li>
                </div>
                <div v-show="errors.type !== undefined">
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
                <div v-show="errors.nif !== undefined">
                    <li>NIF must have 9 numbers!</li>
                </div>
            </ul>
        </div>
    </div>
</template>

<script>
    export default{
        props:['errors'],
    }
</script>
