<template>
    <div>
        <div>
            <form>
                <div class="form-group">
                    <label for="inputFullName">Full Name</label>
                    <input type="text" class="form-control" id="inputFullName" aria-describedby="fullNameHelp" required placeholder="Enter Full Name" v-model="newUser.name">
                    <small id="nameHelp" class="form-text text-muted">Full name can only contain letters and spaces.</small>
                </div>
                <div class="form-group">
                    <label for="inputEmail">Email Address</label>
                    <input type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" required placeholder="Enter email" v-model="newUser.email">
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label for="inputPassword">Password</label>
                        <input type="password" minlength="3" class="form-control" id="inputPassword" placeholder="Password" required v-model.lazy="newUser.password">
                        <small id="passwordHelp" class="form-text text-muted">Password must have 3 or more characters.</small>
                    </div>
                    <div class="form-group col">
                        <label for="inputPasswordConfirm"> Confirm Password</label>
                        <input type="password" class="form-control" id="inputPasswordConfirm" placeholder="Confirm Password" required v-model.lazy="confirmed_password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputNIF">NIF</label>
                    <input type="number" class="form-control" id="inputNIF" aria-describedby="nifHelp" placeholder="Enter NIF" v-model.number="newUser.nif">
                    <small id="nifHelp" class="form-text text-muted">Nif can only have up to 9 numbers.</small>
                </div>
                <div class="form-group">
                    <div>
                        <label for="inputImage">Profile Picture</label>
                        <div class=" custom-file" v-if="newUser.photoURL.length === 0" >
                            <input style="display: none;" ref="inputImage" type="file" accept="image/*" @change="imageUpload" id="inputImage" aria-describedby="imageHelp">
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
                <button v-on:click.prevent="submitUser()" type="submit" class="btn btn-primary">Submit</button>
                <button v-on:click.prevent="cancelRegistration()" class="btn btn-danger">Cancel</button>
            </form>
        </div>
    </div>
</template>

<script>
    import FormData from 'form-data';

    export default {
        data: function(){
            return{
                newUser: {
                    name: '',
                    email: '',
                    password: '',
                    type: 'u',
                    photo: null,
                    photoURL: '',
                    nif: '',
                },
                confirmed_password: '',
            }
        },
        methods:{
            cancelRegistration:function() {
                this.$emit('cancel-registration');
            },
            async submitUser(){
                console.log(this.newUser);
                axios.post("api/users", this.newUser)
                    .then(response => {this.$emit('form-submitted')})
                    .catch(error => console.log(error.message));
            },
            imageUpload:function(event){
                this.newUser.photo = event.target.files[0];
                this.newUser.photoURL = URL.createObjectURL(this.newUser.photo);
                //returned string is too big and database can't be altered, if it could, converting the image file to base64 and storing it as a string would be an option.
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
                this.photo = '';
                this.newUser.photoURL = '';
            }/*,
            base64_encode: function(file) {
                // read binary data
                let bitmap = fs.readFileSync(file);
                // convert binary data to base64 encoded string
                return new Buffer(bitmap).toString('base64');
            }*/
        }
    }
</script>

<style scoped>

</style>
