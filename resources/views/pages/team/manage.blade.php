@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Team Manager @stop

@section('description')Hyperscore Manage Team @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('body')
<div class="jumbotron jumbotron-fluid">
    <div class="jumbotronProfile" style="background-image: url( {{ asset('assets/img/user-background-display.jpg') }} );"></div>
</div>
<div class="container">
    <div class="row">Team</div>
</div>
@stop