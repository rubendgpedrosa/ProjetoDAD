<template>
    <div>
        <div>
            <div class="form-row">
                <div class="col">
                    <h1>{{ title }}</h1>
                </div>
                <div>
                    <div class="col">
                        <div>
                            <img :src="`./storage/fotos/${photo}`" class="rounded-circle" style="max-width:100px;">
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <form>
                <div class="form-group">
                    <label for="inputFullName">Full Name</label>
                    <input type="text" class="form-control" id="inputFullName" placeholder="Enter Full Name" v-model="alteredUser.name">
                    <small id="nameHelp" class="form-text text-muted">Full name can only contain letters and spaces.</small>
                </div>
                <div class="form-group">
                    <label for="inputEmail">Email Address</label>
                    <input type="email" class="form-control" id="inputEmail" disabled placeholder="Enter email" v-model="alteredUser.email">
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label for="inputPassword">Old Password</label>
                        <input @change="invalid_password = false" type="password" minlength="3" class="form-control" id="inputoldPassword" placeholder="Password" v-model="alteredUser.password">
                        <small v-show="invalid_password === true" style="color:red;" class="form-text text-muted"><a style="color:red">Invalid password.</a></small>
                    </div>
                    <div class="form-group col">
                        <label for="inputPassword">New Password</label>
                        <input type="password" minlength="3" class="form-control" id="inputPassword" placeholder="Password" v-model="new_password">
                        <small id="passwordHelp" class="form-text text-muted">New password must have 3 or more characters.</small>
                    </div>
                    <div class="form-group col">
                        <label for="inputPasswordConfirm"> Confirm New Password</label>
                        <input type="password" class="form-control" id="inputPasswordConfirm" placeholder="Confirm Password" v-model="confirmed_password">
                        <small v-show="new_password !== confirmed_password" style="color:red;" class="form-text text-muted"><a style="color:red">Passwords don't match.</a></small>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputNIF">NIF</label>
                    <input type="number" class="form-control" id="inputNIF" placeholder="Enter NIF" maxlength="9" v-model="alteredUser.nif">
                    <small class="form-text text-muted">NIF had to have 9 numbers.</small>
                </div>
                <div class="form-row">
                    <div class="col">
                        <label for="inputImage">Profile Picture</label>
                        <div class="custom-file" v-if="photoURL.length === 0" >
                            <input style="display: none;" ref="inputImage" type="file" accept="image/*" @change="imageUpload" id="inputImage" >
                            <button class="custom-file-label" @click="$refs.inputImage.click()">Upload</button>
                        </div>
                        <div v-else>
                            <img style="width:20%" :src="photoURL"/>
                            <button type="button" class="close" aria-label="Close" v-on:click="clickPhotograph">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <small id="imageHelp" class="form-text text-muted">Upload an optional photograph.</small>
                    </div>
                </div>
                <button :disabled="disableButtonSubmit() === true" v-on:click.prevent="submitChanges()" type="submit" class="btn btn-primary">Submit Changes</button>
            </form>
        </div>
    </div>
</template>

<script>
    //Completed US5
    export default {
        props: ['alteredUser'],
        data: function(){
            return{
                title: 'My Profile',
                photo: '',
                new_password: '',
                confirmed_password: '',
                photoURL: '',
                invalid_password: false,
            }
        },
        methods:{
            imageUpload: function(event){
                this.alteredUser.photo = event.target.files[0];
                this.photoURL = URL.createObjectURL(this.alteredUser.photo);
                this.readFileAsDataURL(this.alteredUser.photo).then(response => {this.alteredUser.photo = response;});
                console.log(this.alteredUser.photo);
            },
            readFileAsDataURL: async function(file) {
                return await new Promise((resolve) => {
                    let fileReader = new FileReader();
                    fileReader.onload = (e) => resolve(fileReader.result);
                    fileReader.readAsDataURL(file);
                });
            },
            submitChanges: function(){
                let self = this;
                this.alteredUser.new_password = this.new_password;
                this.photo = this.alteredUser.photo;
                axios.put(`/api/users/${this.alteredUser.id}`, this.alteredUser)
                    .then(response => {
                        self.$toasted.show('User profile updated!', { type: 'success' });
                    })
                    .catch(error => {console.log(error.message); this.invalid_password = true;
                        this.$toasted.show('Error updating profile!', { type: 'error' });});
            },
            clickPhotograph:function(){
                this.photoURL = '';
            },
            disableButtonSubmit: function(){
                return (this.alteredUser.name === "" || this.new_password !== this.confirmed_password ||
                    (this.alteredUser.password === "" && this.new_password !== "") || (this.alteredUser.password === "" && this.confirmed_password !== ""));
            },
        },
        mounted() {
            this.photo = this.alteredUser.photo;
        }
    }
</script>

<style scoped>

</style>
