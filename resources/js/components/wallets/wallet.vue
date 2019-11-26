s<template>
    <div>
        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Virtual Wallet: </td>
                <td>Current Balance: </td>
                <td>€€</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default{
        props:['balance', 'created_at', 'updated_at', 'id', 'email'],
        name: 'Wallet',
        data: function() {
            return {
                title: 'YadaYada',
                type: 'school',
                category: 'fuel',
                date: '11-11-2019',
            }
        }
    }

</script>

<style scoped>

</style>
