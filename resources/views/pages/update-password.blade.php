@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Update Password @stop

@section('description')HST (Hyper Score Technology) is an action sport management app, scoring system app, and administration in one complete system. It's a cloud based system that ensure the access on almost any internet connected devices will run well. Our services will define that to run the event can become so quick, easy, fun, and professional. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('body')
<div class="container mt-4">
    <main class="card-body mx-auto" style="max-width: 400px;">
        <div class="d-flex justify-content-center align-items-center py-5">
            <img height="60" src="{{ asset('assets/img/logo_light.png') }}" alt="logo">
            <span class="text-white fw-bold fs-2 ms-2">Update Your Password</span>
        </div>
        <form id="form" data="form-ajax" method="post" action="/forceUpdatePassword">
            @csrf
            
            @if(session('errors'))
                <div class="alert alert-danger" role="alert">
                    <!-- Create list -->
                    <ul class="mb-0">
                    @foreach($errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="form-group mb-3">
                <label class="text-white fw-bold mb-1" for="password">Password</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text add-onColor"><i class="fas fa-key mx-auto"></i></span>
                    <input id="password" name="password" type="password" class="form-control input-color" placeholder="Your Password">
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="text-white fw-bold mb-1" for="password_confirmation">Confirm Password</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text add-onColor"><i class="fas fa-key mx-auto"></i></span>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control input-color" placeholder="Confirm Password">
                </div>
            </div>
            <div class="form-group py-5">
                <button class="btn btn-red fw-bold w-100" type="submit">Update Password</button>
                <!-- <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="/register" class="fw-bold text-white">Create Account</a>
                    <a href="/forgot-password" class="fw-bold text-white">Reset Password</a></h4>
                </div> -->
            </div>
        </form>
    </main>
</div>
@stop