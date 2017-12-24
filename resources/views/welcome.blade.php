<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,400,500,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                font-weight: 400;
            }

            .title > a {
				text-decoration: none;
            }

            .small {
                font-size: 42px;
                font-weight: 400;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            ol.css li > a {
                padding: 0 25px;
                font-size: 18px;
                font-weight: 500;
                letter-spacing: .1rem;
            }

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <a href="{{ url('/') }}">
                    	Welcome to my APIRestful
                    </a>
                </div>

                <h2>
                    <small>
                    	Crear fronted con vue.js y como framewok css usar:
                    </small>
                    <ol class="css">
                    	<li>
                    		<a href="http://materializecss.com">Materialize, a CSS Framework based on material design</a>
                    	</li>
                    	<li>
                    		<a href="http://daemonite.github.io/material/">Daemonite/material: Material Design for Bootstrap 4</a>
                    	</li>
                    	<li>
                    		<a href="http://propeller.in">Propeller - Front-end framework based on Material Design &amp; Bootstrap</a>
                    	</li>
                    </ol>
                </h2>

                <div class="links m-b-md">
                    <a href="{{ route('users.index') }}">Users</a>
                    <a href="{{ route('sellers.index') }}">Sellers</a>
                    <a href="{{ route('buyers.index') }}">Buyers</a>
                    <a href="{{ route('categories.index') }}">Categories</a>
                    <a href="{{ route('products.index') }}">Products</a>
                    <a href="{{ route('transactions.index') }}">Transactions</a>
                </div>

                <div class="small m-b-md">
                    <small>Documentation</small>
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>
