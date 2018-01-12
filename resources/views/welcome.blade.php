@extends('layouts.app')

@push('styles')
    <style type="text/css">
        html, body {
            font-family: 'Raleway', sans-serif;
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
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <h1 class="page-header text-center">
                        Welcome to my APIRestful
                    </h1>

                    <h2 class="text-center">
                        <small>
                            Crear fronted con vue.js y como framework css usar:
                        </small>
                        <ol class="css list-unstyled">
                            <li>
                                <a href="https://vuetifyjs.com/">Vuetify.js</a>
                            </li>
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

                    <div class="links m-b-md text-center">
                        <a href="{{ route('users.index') }}">Users</a>
                        <a href="{{ route('sellers.index') }}">Sellers</a>
                        <a href="{{ route('buyers.index') }}">Buyers</a>
                        <a href="{{ route('categories.index') }}">Categories</a>
                        <a href="{{ route('products.index') }}">Products</a>
                        <a href="{{ route('transactions.index') }}">Transactions</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection