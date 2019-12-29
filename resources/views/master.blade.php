<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('metatags')
        <title>@yield('title')</title>
        @yield('extrastyles')
        <!-- Latest compiled and minified CSS & JS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        </head>

<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#/">Homepage</a>
    <ul class="navbar-nav px-3">
    </ul>
</nav>
<div class="container-fluid" id="app">
<div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li><router-link to="/expenses">Register Expense</router-link></li>
                <li><router-link to="/wallet">My Wallet</router-link></li>
                <li><router-link to="/profile">My Profile</router-link></li>
                <li><router-link to="/categories">Categories</router-link></li>
                <li><router-link to="/users">Users</router-link></li>
                <li><router-link to="/login">Login</router-link> </li>
                <li><router-link to="/admin/create">Create Admin Acc</router-link></li>
            </ul>
        </div>
    </nav>
    <main class="container">
        <router-view></router-view>
    </main>
</div>
</div>
    @yield('pagescript')
</body>

</html>
