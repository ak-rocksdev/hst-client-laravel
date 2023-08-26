@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title')Create New Account @stop

@section('description')HST (Hyper Score Technology) is an action sport management app, scoring system app, and administration in one complete system. It's a cloud based system that ensure the access on almost any internet connected devices will run well. Our services will define that to run the event can become so quick, easy, fun, and professional. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('extracss')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('body')
<div class="container mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-center align-items-center py-5">
            <img height="60" src="{{ asset('assets/img/logo_light.png') }}" alt="logo">
            <span class="text-white fw-bold fs-2 ms-2">{{ __('messages.registration_page_title') }}</span>
        </div>
    </div>
    <div id="response" style="display: none"></div>

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
    <!-- NOTE: Add Language, Datepicker, Validation FE, Success Response login Button -->
    <form id="form" data="form-ajax" method="post" action="/api/user/register">
        @csrf
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-12">
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fas fa-user mx-auto"></i></span>
                        <div class="form-floating">
                            <input type="text" class="form-control" name="full_name" id="full_name" placeholder="{{ __('messages.full_name') }}">
                            <label for="full_name">{{ __('messages.full_name') }}</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <div class="form-floating">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                            <label for="email">Email</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                        <div class="form-floating">
                            <input type="date" class="form-control" name="dateofbirth" id="dateofbirth" placeholder="{{ __('messages.dateofbirth') }}">
                            <label for="dateofbirth">{{ __('messages.dateofbirth') }}</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group has-validation mb-3">
                        <span class="d-flex align-items-center fw-bold text-white me-3 mb-2">{{ __('messages.locale') }}</span>
                        <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="locale" value="en" id="english" autocomplete="off">
                            <label class="btn btn-outline-light" for="english">English</label>

                            <input type="radio" class="btn-check" name="locale" value="id" id="indo" autocomplete="off">
                            <label class="btn btn-outline-light" for="indo">Indonesia</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="password" id="password" placeholder="{{ __('messages.your_password') }}">
                            <label for="password">{{ __('messages.your_password') }}</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="{{ __('messages.re_password') }}">
                            <label for="confirm_password">{{ __('messages.re_password') }}</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group">
                        <select class="form-select select2" name="country_code" id="country_code" aria-label="label select">
                            <option value="">{{ __('messages.country_code') }}</option>
                        </select>
                        <div class="form-floating" style="width: unset !important; min-width: 30%;">
                            <input type="text" class="form-control" name="phone" id="phone"
                                placeholder="+62 8123456789" style="border-top-right-radius: 0.375rem !important; border-bottom-right-radius: 0.375rem !important;">
                            <label for="phone">{{ __('messages.phone') }} / WhatsApp</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group py-5 mw-100 d-block mx-auto" style="width: 400px">
            <button class="btn btn-red fw-bold w-100" type="submit">{{ __('messages.create_account') }}</button>
            <div class="d-flex justify-content-between align-items-center pt-2">
                <a href="/login" class="fw-bold text-white">{{ __('messages.login') }}</a>
                <a href="/" class="fw-bold text-white">{{ __('messages.back_to_home') }}</a></h4>
            </div>
        </div>
    </form>
</div>
@stop

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            templateResult: formatState,
            templateSelection: formatState,
            // language: "id",
            dataType: 'json',
            ajax: {
                'url': 'https://www.supaskateboarding.com/api/countries',
                'dataType': 'json',
                data: function (params) {
                    return {
                        term : params.term
                    };
                },
                "method": "GET",
                processResults: function (result) {
                    // $('#city').select2('enable');
                    return {
                        results: $.map(result.data[0], function (item) {
                            return {
                                id: item.phonecode,
                                text: item.name,
                                phone: item.phonecode,
                                flag: item.iso2
                            }
                        })
                    }
                }
            }
        });
        $('.select2-selection').addClass('d-flex align-items-center');
    });

    function formatState (state) {
        if (!state.id) {
            return state.text;
        }

        var baseUrl = "/assets/img/flag/";
        var $state = $(
            '<span><img class="img-flag" width="15"/> <span></span><span class="countrycode"></span></span>'
        );

        $state.find("span").text(state.text);
        $state.find("span.countrycode").text(' (' + state.phone + ')');
        $state.find("img").attr("src", baseUrl + "/" + state.flag.toLowerCase() + ".png");

        return $state;
    };

    $('#form').on('submit', function(e) {
        showLoading();
        e.preventDefault();

        $('.invalid-feedback').html('');
        $('.is-invalid').removeClass('is-invalid');

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

                $.each(messages, function (key, value) {
                    var fieldName = key;
                    var errorMessage = value[0]; // Get the first error message
                    
                    if (errorMessage) {
                        // Field is invalid, remove is-valid class (if exists) and add is-invalid class
                        $('#' + fieldName).removeClass('is-valid').addClass('is-invalid');
                        $('#' + fieldName).closest('.input-group').find('.form-floating').addClass('is-invalid');
                        $('#' + fieldName).closest('.input-group').find('.invalid-feedback').html(errorMessage);
                    } else {
                        // Field is valid, remove is-invalid class (if exists) and add is-valid class
                        $('#' + fieldName).addClass('is-valid').removeClass('is-invalid');
                        $('#' + fieldName).closest('.input-group').find('.form-floating').removeClass('is-invalid');
                        $('#' + fieldName).closest('.input-group').find('.form-floating input').addClass('is-valid');
                        $('#' + fieldName).closest('.input-group').find('.invalid-feedback').html('');
                    }
                });
            }
        };
        api(action, method, form, successCallback, errorCallback)
    });
</script>

@stop