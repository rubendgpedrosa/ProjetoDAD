@extends('master')

@section('title', 'E-Wallet')

@section('content')
<div style="padding-top: 60px;" id="app">
    <main>
        <nav class="text-center navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
            <router-link class="nav-link" to="/">Homepage</router-link>
            <router-link v-show="this.$store.state.user.type === 'u'" class="nav-link" to="/expenses">Register Expenses</router-link>
            <router-link v-show="this.$store.state.user.type === 'u'" class="nav-link" to="/wallet">My Wallet</router-link>
            <router-link v-show="this.$store.state.logged_in" class="nav-link" to="/profile">My Profile</router-link>
            <router-link v-show="this.$store.state.user.type === 'o'" class="nav-link" to="/deposits">Register Incomes</router-link>
            <router-link v-show="this.$store.state.user.type === 'a'" class="nav-link" to="/admin">Administration</router-link>
            <router-link v-show="this.$store.state.user.type === 'a'" class="nav-link" to="/users">Users List</router-link>
        </nav>
        <router-view></router-view>
    </main>
</div>
@endsection

@section('pagescript')
<script src="js/app.js"></script>
@stop

