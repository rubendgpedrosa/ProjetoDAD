<template>
    <div>
        <div>
            <h1 class="display-4">{{ title }}</h1>
            <hr class="my-4">
            <div>
                <errors :errors="validationErrors"></errors>
                <div v-show="userSubmitted">
                    <div class="alert alert-success" role="alert">
                        {{newAdmin.type_user === 'a'? 'Administrator':'Operator'}} account created successfully!
                    </div>
                    <button class="btn btn-primary" @click="newUserSubmit">Create New User</button>
                </div>
            </div>
            <form v-show="userSubmitted === false">
                <div class="form-group">
                    <label for="inputFullName">Full Name*</label>
                    <input required type="text" class="form-control" id="inputFullName" placeholder="Enter Full Name" v-model="newAdmin.name">
                    <small id="nameHelp" class="form-text text-muted">Full name can only contain letters and spaces.</small>
                </div>
                <div class="form-row">
                    <div class="col-2">
                        <label for="inputType">Type of Account*</label>
                        <select class="form-control custom-select" id="inputType" v-model="newAdmin.type_user" required>
                            <option v-for="type_user in types" :value="type_user.value">
                                {{type_user.name}}
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="inputEmail">Email Address*</label>
                        <input type="email" class="form-control" id="inputEmail" required placeholder="Enter email" @change="email_taken = false" v-model="newAdmin.email">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label for="inputPassword">Password*</label>
                        <input type="password" minlength="3" class="form-control" id="inputPassword" placeholder="Password" required v-model="newAdmin.password">
                        <small id="passwordHelp" class="form-text text-muted">Password must have 3 or more characters.</small>
                    </div>
                    <div class="form-group col">
                        <label for="inputPasswordConfirm"> Confirm Password*</label>
                        <input type="password" class="form-control" id="inputPasswordConfirm" placeholder="Confirm Password" required v-model="confirmed_password">
                        <small v-show="newAdmin.password !== confirmed_password" style="color:red;" class="form-text text-muted"><a style="color:red">Passwords don't match.</a></small>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <label for="inputImage">Profile Picture*</label>
                        <div class=" custom-file" v-if="newAdmin.photoURL.length === 0" >
                            <input style="display: none;" ref="inputImage" type="file" accept="image/*" @change="imageUpload" id="inputImage" >
                            <button class="custom-file-label" @click="$refs.inputImage.click()">Upload</button>
                        </div>
                        <div v-else>
                            <img style="width:20%" :src="newAdmin.photoURL"/>
                            <button type="button" class="close" aria-label="Close" v-on:click="clickPhotograph">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div></div>
                        </div>
                    </div>
                </div>
                <button :disabled="disableButtonSubmit() === true" v-on:click.prevent="submitUser()" type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</template>

<script>
    /*US 15. As an Administrator of the platform I want to create operator and administration
    accounts. These accounts will only have a name (only spaces and letters), a photo (upload
    a JPG file), a password (3 or more characters) and an e-mail, which must be unique
    among all accounts of the platform (including the platform users).*/
    import errors from '../utils/errors.vue';

    export default{
        components: {errors},
        data: function(){
            return{
                title: 'Create Privileged Accounts',
                userSubmitted: false,
                newAdmin:{
                    name: '',
                    photo: '',
                    photoURL: '',
                    password: '',
                    email: '',
                    type_user: '',
                },
                confirmed_password: '',
                validationErrors: 'tou',
                types: [{name: 'Administrator', value: 'a'}, {name: 'Operator', value: 'o'}],
            }
        },
        methods:{
            async submitUser(){
                axios.post("api/users", this.newAdmin)
                    .then(response => {this.$store.commit('addUser', response);
                    this.userSubmitted = true;this.$emit('form-submitted');
                    this.$store.state.number_wallets++;this.validationErrors = 'tou';})
                    .catch(error => {
                        if (error.response.status === 422){
                            this.$toasted.show('Error occurred creating new User!', { type: 'error' });
                            this.validationErrors = error.response.data.errors;
                        }});
            },
            imageUpload:function(event){
                this.newAdmin.photo = event.target.files[0];
                this.newAdmin.photoURL = URL.createObjectURL(this.newAdmin.photo);
                this.readFileAsDataURL(this.newAdmin.photo).then(response => this.newAdmin.photo = response);
            },
            newUserSubmit: function(){
                this.userSubmitted = false;
                this.newAdmin.name = '';
                this.newAdmin.photo = '';
                this.newAdmin.photoURL = '';
                this.newAdmin.password = '';
                this.newAdmin.email = '';
                this.newAdmin.type_user = '';
                this.confirmed_password = '';
            },
            readFileAsDataURL: async function(file) {
                return await new Promise((resolve) => {
                    let fileReader = new FileReader();
                    fileReader.onload = (e) => resolve(fileReader.result);
                    fileReader.readAsDataURL(file);
                });
            },
            clickPhotograph:function(){
                this.newAdmin.photo = '';
                this.newAdmin.photoURL = '';
            },
            disableButtonSubmit: function(){
                return (this.newAdmin.password === "" || this.confirmed_password === "" || this.newAdmin.type === '' ||
                    this.newAdmin.name === "" || this.newAdmin.email === "" || this.newAdmin.password !== this.confirmed_password);
            }
        }
    }
</script>

<style scoped>

</style>
