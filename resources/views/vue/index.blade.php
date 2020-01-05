@extends('master')

@section('title', 'E-Wallet')

@section('content')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
<div id="app">
    <main>
        <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark d-flex">
            <router-link class="nav-link" to="/"><a style="color: white;">Homepage</a></router-link>
            <router-link v-show="this.$store.state.user.type === 'u'" class="nav-link" to="/expenses"><a style="color: white;">Register Expenses</a></router-link>
            <router-link v-show="this.$store.state.user.type === 'u'" class="nav-link" to="/wallet"><a style="color: white;">My Wallet</a></router-link>
            <router-link v-show="this.$store.state.logged_in" class="nav-link" to="/profile"><a style="color: white;">My Profile</a></router-link>
            <router-link v-show="this.$store.state.user.type === 'o'" class="nav-link" to="/deposits"><a style="color: white;">Register Incomes</a></router-link>
            <router-link v-show="this.$store.state.user.type === 'a'" class="nav-link" to="/admin"><a style="color: white;">Administration</a></router-link>
            <router-link v-show="this.$store.state.user.type === 'a'" class="nav-link" to="/users"><a style="color: white;">Users List</a></router-link>
            <div v-if="this.$store.state.logged_in === true" class="ml-auto" style="float-left align-right">
                <button @click="logout" class="btn btn-small "><a style="color: white;text-decoration: underline;">Sign Out</a></button>
                <img :src="`./storage/fotos/${this.$store.state.user.photo === null? 'default.png':this.$store.state.user.photo}`" class="rounded-circle" style="max-width:30px;">
            </div>
        </nav>
        <router-view></router-view>
    </main>
</div>
@endsection

@section('pagescript')
<script src="js/app.js"></script>
@stop

