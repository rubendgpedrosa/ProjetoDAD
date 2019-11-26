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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/style.css">
        </head>

<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Wallet</a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link">Sign</a>
        </li>
    </ul>
</nav>
<div class="container-fluid" id="app">
<div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li><router-link to="/movements">Movements</router-link></li>
                <li><router-link to="/users">Users</router-link></li>
                <li><router-link to="/categories">Categories</router-link></li>
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
