@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title')Edit Profile @stop

@section('description')HST (Hyper Score Technology) is an action sport management app, scoring system app, and administration in one complete system. It's a cloud based system that ensure the access on almost any internet connected devices will run well. Our services will define that to run the event can become so quick, easy, fun, and professional. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('extracss')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
    .h-58px {
        height: 58px;
    }
    .dropzone {
        text-align: center;
        padding: 20px;
        border: 3px dashed #aeaeae;
        background-color: #fafafa;
        color: #bdbdbd;
        margin-bottom: 20px;
    }
</style>
@stop

@section('body')
<div class="container mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-center align-items-center py-5">
            <img height="60" src="{{ asset('assets/img/logo_light.png') }}" alt="logo">
            <span class="text-white fw-bold fs-2 ms-2">{{ __('messages.edit_profile') }}</span>
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
    <form id="form" data="form-ajax" method="put" action="/api/user/update">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12 mb-5">
                    @php
                        $file1 = $user->photoFile ? '/user/' . $user->photoFile : '-';
                        $file2 = '/user/' . $user->email . '.jpg';
                        $defaultFile = '/assets/img/avatar400x400.jpg';

                        $storage = Storage::disk('public');

                        if ($storage->exists($file1)) {
                            $profileUrl = asset('storage' . $file1);
                        } elseif ($storage->exists($file2)) {
                            $profileUrl = asset('storage' . $file2);
                        } else {
                            $profileUrl = asset($defaultFile);
                        }
                    @endphp
                    <div class="avatar-container position-relative">
                        <img class="profileAvatar w-100 me-0 mb-3" src="{{ $profileUrl }}">
                        <span class="image-zoom-button position-absolute bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#zoom-photo-modal">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </span>
                    </div>
                    <span class="btn btn-red fw-bold w-100 mb-2" data-bs-toggle="modal" data-bs-target="#update-photo-modal">{{ __('messages.update_photo') }}</span>
                    <span class="btn btn-red fw-bold w-100" data-bs-toggle="modal" data-bs-target="#update-password-modal">{{ __('messages.change_password') }}</span>
                </div>
                <div class="col-lg-9 col-md-8 col-12">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-12">
                            <div class="input-group has-validation mb-3">
                                <span class="input-group-text"><i class="fas fa-user mx-auto"></i></span>
                                <div class="form-floating">
                                    <input type="text" class="form-control" value="{{ $user->full_name }}" name="full_name" id="full_name" placeholder="{{ __('messages.full_name') }}">
                                    <label for="full_name">{{ __('messages.full_name') }}</label>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="input-group has-validation mb-3">
                                <span class="input-group-text"><i class="fas fa-user mx-auto"></i></span>
                                <div class="form-floating">
                                    <input type="text" class="form-control" value="{{ $user->nick_name }}" name="nick_name" id="nick_name" placeholder="{{ __('messages.nickname') }}">
                                    <label for="nick_name">{{ __('messages.nickname') }}</label>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="input-group has-validation mb-3">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <div class="form-floating">
                                    <input type="email" class="form-control" value="{{ $user->email }}" name="email" id="email" placeholder="Email">
                                    <label for="email">Email</label>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="input-group has-validation mb-3">
                                <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                                <div class="form-floating">
                                    <input type="date" class="form-control" value="{{ $user->dateofbirth }}" name="dateofbirth" id="dateofbirth" placeholder="{{ __('messages.dateofbirth') }}">
                                    <label for="dateofbirth">{{ __('messages.dateofbirth') }}</label>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="input-group has-validation mb-3">
                                <span class="d-flex align-items-center fw-bold text-white me-3 mb-2">{{ __('messages.locale') }}</span>
                                <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="locale" value="en" id="english" autocomplete="off" {{ $user->locale == 'en' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-light" for="english">English</label>

                                    <input type="radio" class="btn-check" name="locale" value="id" id="indo" autocomplete="off" {{ $user->locale == 'id' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-light" for="indo">Indonesia</label>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="input-group has-validation mb-3">
                                <span class="d-flex align-items-center fw-bold text-white me-3 mb-2">Stance</span>
                                <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="stance" value="regular" id="regular" autocomplete="off" {{ $user->stance == '1' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-light" for="regular">Regular</label>

                                    <input type="radio" class="btn-check" name="stance" value="goofy" id="goofy" autocomplete="off" {{ $user->stance == '2' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-light" for="goofy">Goofy</label>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="input-group has-validation mb-3">
                                <span class="input-group-text">Instagram</span>
                                <div class="form-floating">
                                    <input type="text" class="form-control" value="{{ $user->instagram }}" name="instagram" id="instagram" placeholder="Instagram Account">
                                    <label for="instagram">www.instagram.com/</label>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="input-group">
                                <select class="form-select select2" name="country_code" id="country_code" aria-label="label select" style="width: 50%; !important">
                                    <option value="">{{ __('messages.country_code') }}</option>
                                </select>
                                <div class="form-floating" style="width: unset !important; min-width: 30%;">
                                    <input type="text" class="form-control" value="{{ $user->phone }}" name="phone" id="phone"
                                        placeholder="+62 8123456789" style="border-top-right-radius: 0.375rem !important; border-bottom-right-radius: 0.375rem !important;">
                                    <label for="phone">{{ __('messages.phone') }} / WhatsApp</label>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <span class="d-flex align-items-center fw-bold text-white me-3 my-4">{{ __('messages.origin') }}</span>
                            <div class="input-group mt-3 h-58px">
                                <span class="input-group-text" id="basic-addon1">{{ __('messages.country') }}</span>
                                <select class="form-select select2 form-select-lg" name="country" id="country" aria-label="label select">
                                    <option value="">{{ __('messages.country') }}</option>
                                </select>
                            </div>
                            <!-- If the selection value != 'id', show this elements -->
                            <div class="input-group mt-3 h-58px statesContainer" style="display: none">
                                <span class="input-group-text" id="basic-addon1">{{ __('messages.states') }}</span>
                                <select class="form-select select2 form-select-lg" name="states" id="states" aria-label="label select">
                                    <option value="">{{ __('messages.states') }}</option>
                                </select>
                            </div>
                            <div class="input-group mt-3 h-58px cityContainer" style="display: none">
                                <span class="input-group-text" id="basic-addon1">{{ __('messages.city') }}</span>
                                <select class="form-select select2 form-select-lg" name="city" id="city" aria-label="label select">
                                    <option value="">{{ __('messages.city') }}</option>
                                </select>
                            </div>
                            <!-- If the selection value == 'id', show this elements -->
                            <div class="input-group mt-3 h-58px provinceContainer" style="display: none">
                                <span class="input-group-text" id="basic-addon1">{{ __('messages.province') }}</span>
                                <select class="form-select select2 form-select-lg" name="province" id="province" aria-label="label select">
                                    <option value="">{{ __('messages.province') }}</option>
                                </select>
                            </div>
                            <div class="input-group mt-3 h-58px indoCityContainer" style="display: none">
                                <span class="input-group-text" id="basic-addon1">{{ __('messages.city') }}</span>
                                <select class="form-select select2 form-select-lg" name="indoCity" id="indoCity" aria-label="label select">
                                    <option value="">{{ __('messages.city') }}</option>
                                </select>
                            </div>
                            <div class="input-group mt-3 h-58px districtContainer" style="display: none">
                                <span class="input-group-text" id="basic-addon1">{{ __('messages.district') }}</span>
                                <select class="form-select select2 form-select-lg" name="district" id="district" aria-label="label select">
                                    <option value="">{{ __('messages.district') }}</option>
                                </select>
                            </div>
                            <div class="input-group mt-3 h-58px subdistrictContainer" style="display: none">
                                <span class="input-group-text" id="basic-addon1">{{ __('messages.subdistrict') }}</span>
                                <select class="form-select select2 form-select-lg" name="subdistrict" id="subdistrict" aria-label="label select">
                                    <option value="">{{ __('messages.subdistrict') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group py-5 mw-100 d-block mx-auto" style="width: 400px">
                            <button class="btn btn-red fw-bold w-100 mb-2" type="submit">{{ __('messages.save') }}</button>
                            <a href="/profile" class="btn button-black fs-4 fw-bold w-100">{{ __('messages.back_to_profile') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="update-photo-modal" tabindex="-1" aria-labelledby="update-photo-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-black fs-5" id="update-photo-modal-label">Update Photo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="response-photo-upload" style="display: none"></div>
                <form action="/api/user/photo-profile/update" method="post" id="photo-upload-form" class="dropzone"></form>
            </div>
            <div class="modal-footer">
                <span id="cancel-upload" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</span>
                <span id="upload-photo" class="btn btn-primary">Upload</span>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="update-password-modal" tabindex="-1" aria-labelledby="update-password-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-black fs-5" id="update-password-modal-label">{{ __('messages.update_password') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="update-password-form" action="/api/user/update-password" method="put">
                @csrf
                <div class="modal-body">
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="old_password" id="old_password" placeholder="{{ __('messages.old_password') }}">
                            <label for="old_password">{{ __('messages.old_password') }}</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="{{ __('messages.new_password') }}">
                            <label for="new_password">{{ __('messages.new_password') }}</label>
                            </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" placeholder="{{ __('messages.confirm_new_password') }}">
                            <label for="new_password_confirmation">{{ __('messages.confirm_new_password') }}</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.update_password') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal modal-lg fade" id="zoom-photo-modal" tabindex="-1" aria-labelledby="zoom-photo-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img class="w-100" src="{{ $profileUrl }}">
                <h3 class="text-center fw-bold text-black fs-5 mt-3" id="zoom-photo-label">{{ $user->full_name }}</h3>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
    let country_id = "{{ $user->country_id ? $user->country_id : '' }}";
    let user_id = "{{ $user->ID_user }}";

    let currentUserPhoneCode = "{{ $user->country_code }}";
    let currentUserCountryCodeId = "{{ $user->country_code_id }}";
</script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/geo-location-options.js') }}"></script>
<script src="{{ asset('js/image-profile-upload.js') }}"></script>
<script>
    $('#form').on('submit', function(e) {
        showLoading();
        e.preventDefault();

        $('.invalid-feedback').html('');
        $('.is-invalid').removeClass('is-invalid');

        let form = $('#form').serialize();

        form += '&country_name=' + encodeURIComponent($('#country').select2('data')[0].text);
        form += '&state_name=' + encodeURIComponent($('#states').val() ? $('#states').select2('data')[0].text : '');
        form += '&city_name=' + encodeURIComponent($('#city').val() ? $('#city').select2('data')[0].text : '');
        form += '&indo_province_name=' + encodeURIComponent($('#province').val() ? $('#province').select2('data')[0].text : '');
        form += '&indo_city_name=' + encodeURIComponent($('#indoCity').val() ? $('#indoCity').select2('data')[0].text : '');
        form += '&country_code_id=' + encodeURIComponent($('#country_code').val() ? $('#country_code').select2('data')[0].flag : '');

        
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
<script>
    // when form with id="update-password-form" submitted do ajax call to submit form
    $('#update-password-form').on('submit', function(e) {
        showLoading();
        e.preventDefault();

        $('.invalid-feedback').html('');
        $('.is-invalid').removeClass('is-invalid');

        let form = $('#update-password-form').serialize();
        let method = $('#update-password-form').attr('method');
        let action = $('#update-password-form').attr('action');

        successCallback = function(response) {
            hideLoading();
            $('#response').slideUp();
            $('#response-password-container').empty();
            // close modal
            $('#update-password-modal').modal('hide');
            // reset form
            $('#update-password-form').trigger('reset');

            $('#response').empty();

            let messages = response.messages;

            let errorHtml = '<div class="alert alert-success" role="alert">';
            Object.keys(messages).forEach(function(field) {
                errorHtml += `${messages}<br>`;
            });
            errorHtml += '</div>';

            $('#response').html(errorHtml);

            slideDownResponse().then(() => {
                $('#response').slideUp();
            });
        };
        errorCallback = function (xhr) {
            hideLoading();
            $('#response').empty();

            if (xhr.responseJSON && xhr.responseJSON.messages) {
                let messages = xhr.responseJSON.messages;

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

    function slideDownResponse() {
        return new Promise((resolve) => {
            $('#response').slideDown();
            setTimeout(function() {
                resolve();
            }, 4000);
        });
    }
</script>
@stop