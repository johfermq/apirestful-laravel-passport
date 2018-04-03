@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <h1 class="page-header text-center">
                        My authorized clients
                    </h1>

                    <passport-authorized-clients></passport-authorized-clients>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection