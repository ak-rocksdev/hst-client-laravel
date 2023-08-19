@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Login Hyperscore @stop

@section('description')HST (Hyper Score Technology) is an action sport management app, scoring system app, and administration in one complete system. It's a cloud based system that ensure the access on almost any internet connected devices will run well. Our services will define that to run the event can become so quick, easy, fun, and professional. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('body')
<div class="container mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-center align-items-center py-5">
            <img height="60" src="{{ asset('assets/img/logo_light.png') }}" alt="logo">
            <span class="text-white fw-bold fs-2 ms-2">{{ __('messages.login_page_title') }}</span>
        </div>
    </div>
    <main class="card-body mx-auto" style="max-width: 400px;">
        <div id="response" style="display: none"></div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <form id="form" data="form-ajax" method="post" action="/doLogin">
            @csrf
            <div class="form-group mb-3">
                <label class="text-white fw-bold mb-1" for="email">Email</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text add-onColor"><i class="fas fa-at mx-auto"></i></span>
                    <input id="email" name="email" class="form-control input-color" placeholder="example@email.com" autocomplete="off">
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="text-white fw-bold mb-1" for="password">{{ __('messages.password') }}</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text add-onColor"><i class="fas fa-key mx-auto"></i></span>
                    <input id="password" name="password" type="password" class="form-control input-color" placeholder="{{ __('messages.your_password') }}">
                </div>
            </div>
            <div class="form-group py-5">
                <button class="btn btn-red fw-bold w-100" type="submit">{{ __('messages.login') }}</button>
                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="/register" class="fw-bold text-white">{{ __('messages.create_account') }}</a>
                    <a href="/forgot-password" class="fw-bold text-white">{{ __('messages.reset_password') }}</a></h4>
                </div>
            </div>
        </form>
    </main>
</div>
@stop

@section('script')
<script>
    $('#form').on('submit', function(e) {
        showLoading();
        e.preventDefault();

        let form = $('#form').serialize();
        let method = $('#form').attr('method');
        let action = $('#form').attr('action');

        successCallback = function(response) {
            hideLoading();
            $('#response').slideUp();
            if(response.redirect) {
                window.location.href = response.redirect;
            }
        };
        errorCallback = function (xhr) {
            hideLoading();
            $('#response').empty();

            if (xhr.responseJSON && xhr.responseJSON.messages) {
                let messages = xhr.responseJSON.messages;
                console.log(messages)

                let errorHtml = '<div class="alert alert-danger" role="alert">';
                Object.keys(messages).forEach(function(field) {
                    errorHtml += `${messages[field][0]}<br>`;
                });
                errorHtml += '</div>';

                $('#response').html(errorHtml);
                $('#response').slideDown();
            }
        };
        api(action, method, form, successCallback, errorCallback)
    });
</script>

@stop