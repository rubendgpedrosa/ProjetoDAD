<template>
    <div>
        <div class="form-group">

            <label for="name">Nome Completo</label>
            <input
                type="text" class="form-control"
                name="name" id="name"
                placeholder="Nome" value=""/>

            <button type="button" class="btn btn-danger" v-on:click.prevent="cancelEdit()">Cancel</button>
    </div>
    </div>

</template>
<script>
export default {
    methods:{
        cancelEdit:function() {
            console.log('YO');
            this.$emit('cancel-edit');
        }
    }
}

</script>
<style>


</style>
