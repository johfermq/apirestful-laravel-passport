@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <h1 class="page-header text-center">
                        My personal tokens
                    </h1>

                    <passport-personal-access-tokens></passport-personal-access-tokens>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection