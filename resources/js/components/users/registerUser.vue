<template>
    <div>
        <div>
            <div>
                <errors :errors="validationErrors"></errors>
            </div>
            <form>
                <div class="form-group">
                    <label for="inputFullName">Full Name*</label>
                    <input required type="text" class="form-control" id="inputFullName" placeholder="Enter Full Name" v-model="newUser.name">
                    <small id="nameHelp" class="form-text text-muted">Full name can only contain letters and spaces.</small>
                </div>
                <div class="form-group">
                    <label for="inputEmail">Email Address*</label>
                    <input type="email" class="form-control" id="inputEmail" required placeholder="Enter email" v-model="newUser.email">
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label for="inputPassword">Password*</label>
                        <input type="password" minlength="3" class="form-control" id="inputPassword" placeholder="Password" required v-model="newUser.password">
                        <small id="passwordHelp" class="form-text text-muted">Password must have 3 or more characters.</small>
                    </div>
                    <div class="form-group col">
                        <label for="inputPasswordConfirm"> Confirm Password*</label>
                        <input type="password" class="form-control" id="inputPasswordConfirm" placeholder="Confirm Password" required v-model="confirmed_password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputNIF">NIF</label>
                    <input type="number" class="form-control" id="inputNIF" placeholder="Enter NIF" v-model="newUser.nif">
                </div>
                <div class="form-group">
                    <div>
                        <label for="inputImage">Profile Picture</label>
                        <div class=" custom-file" v-if="newUser.photoURL.length === 0" >
                            <input style="display: none;" ref="inputImage" type="file" accept="image/*" @change="imageUpload" id="inputImage" >
                            <button class="custom-file-label" @click="$refs.inputImage.click()">Upload</button>
                        </div>
                        <div v-else>
                            <img style="width:20%" :src="newUser.photoURL"/>
                            <button type="button" class="close" aria-label="Close" v-on:click="clickPhotograph">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div></div>
                        </div>
                        <small id="imageHelp" class="form-text text-muted">Upload an optional photograph.</small>
                    </div>
                </div>
                <button :disabled="disableButtonSubmit() === true" v-on:click.prevent="submitUser()" type="submit" class="btn btn-primary">Submit</button>
                <button v-on:click.prevent="cancelRegistration()" class="btn btn-danger">Cancel</button>
            </form>
        </div>
    </div>
</template>

<script>
    import errors from '../utils/errors.vue';

    export default {
        components: { errors },
        data: function(){
            return{
                newUser: {
                    name: '',
                    email: '',
                    password: '',
                    photo: null,
                    photoURL: '',
                    nif: '',
                },
                confirmed_password: '',
                validationErrors: 'tou',
            }
        },
        methods:{
            cancelRegistration:function() {
                this.$emit('cancel-registration');
            },
            async submitUser(){
                axios.post("api/registration", this.newUser)
                    .then(response => {this.$emit('form-submitted');
                        this.$store.state.number_wallets++;
                        this.$toasted.show('Account sucessfully created', { type: 'success' });
                        this.$store.commit('addUser', response);})
                    .catch(error => { console.log(error.message);
                        if (error.response.status === 400){
                            this.$toasted.show('Error occurred creating new User!', { type: 'error' });
                            this.validationErrors = error.response.data.errors;
                            this.$socket.emit('user_created', this.newUser.email);
                        }});
            },
            imageUpload:function(event){
                this.newUser.photo = event.target.files[0];
                this.newUser.photoURL = URL.createObjectURL(this.newUser.photo);
                this.readFileAsDataURL(this.newUser.photo).then(response => this.newUser.photo = response);
            },
            readFileAsDataURL: async function(file) {
                return await new Promise((resolve) => {
                    let fileReader = new FileReader();
                    fileReader.onload = (e) => resolve(fileReader.result);
                    fileReader.readAsDataURL(file);
                });
            },
            clickPhotograph:function(){
                this.newUser.photo = '';
                this.newUser.photoURL = '';
            },
            disableButtonSubmit: function(){
                return (this.newUser.password === "" || this.confirmed_password === "" || this.newUser.name === "" || this.newUser.email === "" || this.newUser.password !== this.confirmed_password);
            }
        }
    }
</script>

<style scoped>

</style>
