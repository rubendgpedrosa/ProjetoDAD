<template>
    <div>
        <table class="table table-hover table-borderless">
            <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Start Balance</th>
                <th>End Balance</th>
                <th>Value Transfered</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-model="movementInformationClicked">
                <td>{{movementClicked.date}}</td>
                <td>{{movementClicked.type_string}}</td>
                <td>{{movementClicked.category_string}}</td>
                <td>{{movementClicked.start_balance}}</td>
                <td>{{movementClicked.end_balance}}</td>
                <td>{{movementClicked.value}}</td>
                <td><button class="glyphicon glyphicon-remove" @click="$emit('movement-information-clicked')"></button></td>
            </tr>
            </tbody>
        </table>
        <div style="position: relative;">
            <div class="card-body card-block" v-if="editMovementClicked === false">
                <div class="form-row">
                    <div class="col">
                        <label for="inputDescription" style="float:left;">Description</label>
                        <textarea class="form-control" readonly :value="movementClicked.description === null? 'Not Available':movementClicked.description"/>
                    </div>
                    <div class="col-2" style="padding-left:20px;">
                        <div class="form-row">
                            <label for="inputDescription" style="float:left;">Photo</label>
                        </div>
                        <div class="form-row">
                            <img :src="`./storage/fotos/${movementClicked.photo === undefined? 'default.png':movementClicked.photo}`" class="rounded" style="max-width:65px;"/>
                        </div>
                    </div>
                </div>
                <div v-if="movementClicked.source_description !== null" class="form-group">
                    <label for="inputDescription" style="float:left;">Source Description</label>
                    <textarea class="form-control" readonly :value="movementClicked.source_description"/>
                </div>
                <div v-if="movementClicked.iban !== null" class="form-group">
                    <label for="inputDescription" style="float:left;">IBAN</label>
                    <input type="text" class="form-control" readonly :value="movementClicked.iban">
                </div>
                <div v-if="movementClicked.mb_entity_code !== null" class="form-group">
                    <label for="inputDescription" style="float:left;">MB Entity Code</label>
                    <input type="text" class="form-control" readonly :value="movementClicked.mb_entity_code">
                </div>
                <div v-if="movementClicked.mb_payment_reference !== null" class="form-group">
                    <label for="inputDescription" style="float:left;">MB Payment Reference</label>
                    <input type="text" class="form-control" readonly :value="movementClicked.mb_payment_reference">
                </div>
            </div>
            <div class="card-body card-block" v-else>
                <div class="form-group">
                    <label for="inputDescription" style="float:left;">Description</label>
                    <textarea type="text" class="form-control" id="inputDescription" aria-describedby="descriptionHelp" v-model="movementClicked.description"/>
                    <small id="descriptionHelp" class="form-text text-muted">Please enter your new description.</small>
                </div>
                <div class="form-group">
                    <label for="inputCategory">Category</label>
                    <select class="form-control" id="inputCategory" aria-describedby="categoryHelp" required v-model="movementClicked.category_id">
                        <option v-for="category in this.$store.state.categories" :value="category.id">
                            {{category.name}}
                        </option>
                    </select>
                    <small id="categoryHelp" class="form-text text-muted">Please select your new category.</small>
                </div>
                <button v-on:click.prevent="updateMovement()" type="submit" class="btn btn-primary">Submit</button>
                <button v-on:click.prevent="toggleEditMovement()" class="btn btn-danger">Cancel</button>
            </div>
            <div v-if="editMovementClicked === false" type="button" class="btn btn-primary btn-sm" style="position: absolute; right: 1%; top: -1px;" @click="toggleEditMovement">
                <span class="glyphicon glyphicon-pencil"/>
            </div>
        </div>
    </div>

</template>

<script>
    //Completed US11
    export default{
        props:['movementClicked','movementInformationClicked'],
        data: function(){
                return {
                    editMovementClicked: false,
                    selected: '',
                    newData: {}
                }
        },
        methods: {
            toggleEditMovement: function () {
                this.editMovementClicked = this.editMovementClicked === false;
                this.$emit('more-information-closed');
            },
            updateMovement: function(){
                this.newData.category_id = this.movementClicked.category_id;
                this.newData.description = this.movementClicked.description;
                this.newData.id = this.movementClicked.id;
                axios.put('/api/movements', this.newData)
                    .then(response=>{
                        if(response.status === 200){
                            this.toggleEditMovement();
                            this.movementClicked.category_string = this.$store.state.categories.find(category => category.id === this.movementClicked.category_id).name;
                            this.$toasted.show('Movement successfully edited!', { type: 'success' });
                        }
                    })
                    .catch(error=>{this.$toasted.show('Error updating the movement!', { type: 'error' }); console.log(error.message)});
            }
        }
    }
</script>

<style scoped>

</style>
