@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Profile @stop

@section('description')Hyperscore User Profile and Information @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('extracss')
<style>
    .checkbox-lg .form-check-input {
        scale: 1.5;
        left: 3px;
        position: relative;
    }

    .form-check-label {
        text-align: justify;
    }
</style>
@stop

@section('body')
<div class="jumbotron jumbotron-fluid">
    <div class="jumbotronProfile" style="background-image: url( {{ asset('assets/img/user-background-display.jpg') }} );"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-10 col-12 d-flex profileTopContainer">
            @php
                $user->photoFile == null ? $file1 = '-' : $file1 = '/user/' . $user->photoFile;
                $file2 = '/user/' . $user->email . '.jpg';
                $defaultFile = '/assets/img/avatar400x400.jpg';
                if (Storage::disk('public')->exists($file1)) {
                    $profileUrl = asset('storage' . $file1);
                } else if (Storage::disk('public')->exists($file2)) {
                    $profileUrl = asset('storage' . $file2);
                } else{
                    $profileUrl = asset($defaultFile);
                }
            @endphp
            <img class="profileAvatar" src="{{ $profileUrl }}">
            <div class="ml-3">
                <div class="position-relative">
                    <h2 class="titleContent">
                        {{ $user->full_name }} </br>({{ $user->nick_name ?? '-' }})
                        <span class="verifiedIcon">
                            <sup><i class="fas fa-check-circle"></i></sup>
                        </span>
                    </h2>
                    <span>
                        <p class="textSubtitle">{{ __('messages.hyperscore_experience') }}</p>
                    </span>
                    <span class="starContainer">
                        @php
                            $eventCount = count($events);
                            $filledStars = 0;

                            switch (true) {
                                case ($eventCount == 1):
                                    $filledStars = 1;
                                    break;
                                case ($eventCount >= 2 && $eventCount <= 3):
                                    $filledStars = 2;
                                    break;
                                case ($eventCount >= 4 && $eventCount <= 5):
                                    $filledStars = 3;
                                    break;
                                case ($eventCount >= 6 && $eventCount <= 9):
                                    $filledStars = 4;
                                    break;
                                case ($eventCount >= 10):
                                    $filledStars = 4;
                                    break;
                                default:
                                    $filledStars = 0;
                                    break;
                            }

                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $filledStars) {
                                    echo '<i class="fas fa-star starLevel"></i>';
                                } else {
                                    echo '<i class="far fa-star starLevel"></i>';
                                }
                            }
                        @endphp
                    </span>
                </div>
            </div>
        </div>
        <!-- <div class="col-12 marginTopMinus3">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="userStat">
                        <p>On Contest</p>
                        <span id="onContest" class="value">
                            <i class="fas fa-running me-2" aria-hidden="true"></i>
                            <span>{{ count($events) }}</span>
                        </span>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="userStat">
                        <p>Podium</p>
                        <span id="winContest" class="value">
                            <i class="fas fa-trophy me-2" aria-hidden="true"></i>
                            <span>0</span>
                        </span>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="userStat">
                        <p>HST Credit</p>
                        <div class="d-flex align-items-center value" style="padding-left: 6px !important;">
                            <span class="dollarContainer me-2" id="credit">
                                <i class="fas fa-dollar-sign text-white" aria-hidden="true"></i>
                            </span>
                            <span class="text-black">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end">
                <span class="btn btn-sm btn-dark me-2" data-bs-toggle="modal" data-bs-target="#teamManagerApplicationModal" style="font-size: 1rem;">
                    <i class="fas fa-user-tie me-2"></i>
                    <span>Apply for Team Manager</span>
                </span>
                <a href="/team/manage" class="btn btn-sm btn-dark me-2" style="font-size: 1rem;">
                    <i class="fas fa-users me-2"></i>
                    <span>Manage My Team</span>
                </a>
                <a href="/profile/edit" class="btn btn-sm btn-dark" style="font-size: 1rem;">
                    <i class="fas fa-user-edit me-2"></i>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-lg-4 col-sm-6">
            <div class="info-box hover-expand-effect">
                <div class="icon">
                    <i class="fas fa-snowboarding"></i>
                </div>
                <div class="content">
                    <div class="text">Skateboarding</div>
                    <div class="number count-to" data-from="0" data-to="{{ count($events) }}" data-speed="1000" data-fresh-interval="20">{{ count($events) }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="info-box hover-expand-effect">
                <div class="icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="content">
                    <div class="text">Judging</div>
                    <div class="number count-to" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">-</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="info-box hover-expand-effect" data-bs-toggle="modal" data-bs-target="#user-qr">
                <div class="icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="content">
                    <div class="text">Your ID Code</div>
                    <div class="number count-to" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">QR</div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="titleContent hstCareerTitle text-uppercase"></div>
                <div class="dropdownMenu mt-2">
                    <label for="year" class="text-uppercase fw-bold text-white me-3">Year</label>
                    <div class="form-group d-inline-block">
                        <select id="year" class="form-control">
                            <option>2020</option>
                            <option>2019</option>
                            <option>2018</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex flex-row">
                <div class="table-header-gradient w-100 d-flex flex-row align-items-center p-3">
                    <img class="me-3" src="{{ asset('assets/img/logo_light.png') }}">
                    <div class="d-flex flex-column table-title-container">
                        <div class="fw-bold fs-3">Hyperscore Career</div>
                        <div class="fw-normal">Event</div>
                    </div>
                </div>
                <div class="pointContainer text-center">
                    <div class="titleContent" style="font-size: 1.2rem;">
                        {{ __('messages.event_experience') }}
                        <span style="color: #ffcc00" class="total_point d-block">{{ count($events) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div style="max-width: 100%;overflow-x: auto;">
                <table id="myTable" class="point table table-dark table-striped table-borderless">
                    <thead>
                        <tr>
                            <th width="5%">
                                <div class="titleContent text-uppercase text-center">No</div>
                            </th>
                            <th width="20%">
                                <div class="titleContent text-uppercase text-center">{{ __('messages.event_date') }}</div>
                            </th>
                            <th width="20%">
                                <div class="titleContent text-uppercase">{{ __('messages.event_name') }}</div>
                            </th>
                            <th width="15%">
                                <div class="titleContent text-uppercase">{{ __('messages.event_category') }}</div>
                            </th>
                            <th width="10%" class="text-center">
                                <div class="titleContent text-uppercase">{{ __('messages.position') }}</div>
                            </th>
                            <th width="15%" class="text-center">
                                <div class="titleContent text-uppercase">{{ __('messages.score') }}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- if $events empty show the message -->
                        @if (count($events) == 0)
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="titleContent">{!! __('messages.no_event_message') !!}</div>
                            </td>
                        </tr>
                        @else
                            @foreach ($processedScores as $processedScore)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($processedScore['start_date'])->format('d M Y') }}</td>
                                <td><a class="event-link" href="/event/{{ $processedScore['ID_event'] }}">{{ $processedScore['event_name'] }}</a></td>
                                <td>{{ $processedScore['event_category'] }}</td>
                                <td class="text-center">{{ $processedScore['ID_type'] }}</td>
                                <td class="text-center">{{ number_format($processedScore['total_score'], 2) }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

</div>
<!-- MODAL -->
<div id="user-qr" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header p-4 d-flex flex-row justify-content-center align-items-center border-0 bg-white">
                <div id="qrcodeCanvas">
                    <div class="text-center d-flex flex-row justify-content-center align-items-center">
                        <img src="{{ asset('assets/img/logo_light.png') }}" class="me-2" alt="Logo" height="50">
                        <h2 class="text-center text-black fw-bold m-0">Your Hyperscore ID</h2>
                    </div>
                    <div id="qr-container" class="bg-white border border-5 border-white p-3 d-flex justify-content-center"></div>
                    <div class="text-center mt-3">
                        <span id="user-id" class="fw-bold fs-4 bg-danger py-1 px-3 rounded-3 text-white">HST-{{ str_pad($user->ID_user, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
            <!-- button close on footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger fw-bold text-uppercase" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal terms and condition to apply as team manager -->
<div class="modal modal-lg fade" id="teamManagerApplicationModal" tabindex="-1" aria-labelledby="teamManagerApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-dark fw-bold">Apply as Team Manager</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="team-manager-application-form" action="/api/team/apply" method="post">
                @csrf
                <div class="modal-body p-5 text-dark">
                    <div id="response" style="display: none"></div>
                    <div class="accordion mb-3" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <span class="fw-bold me-1">Read</span> the Team Manager Agreement
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div id="tnc-container" class="tnc-container">
                                        <h2 class="text-center fw-bold mb-4">Team Manager Agreement (Terms and Conditions)</h2>
                                        <ol>
                                            <li>
                                                <h4>Account Management:</h4>
                                                <p>
                                                    <ul>
                                                        <li>
                                                            As a team manager, you will be responsible for managing the athlete's account until the athlete reaches an age where they can manage their own account.
                                                        </li>
                                                        <li>
                                                            The athlete, upon reaching the required age, has the right to take over and manage their own account.
                                                        </li>
                                                    </ul>
                                                </p>
                                            </li>

                                            <li>
                                                <h4>Disconnecting from Management:</h4>
                                                <p>
                                                    The athlete has the right to disconnect from you as a team manager through the disconnect application at any time.
                                                </p>
                                            </li>

                                            <li>
                                                <h4>Responsibility for Minor Athletes:</h4>
                                                <p>
                                                    In cases where the athlete is a minor and cannot manage their own account, the team manager is fully responsible for the athlete's account.
                                                </p>
                                            </li>

                                            <li>
                                                <h4>Indonesian Athletes:</h4>
                                                <p>
                                                    <ul>
                                                        <li>
                                                            Indonesian athletes may be managed by their respective provinces with their consent.
                                                        </li>
                                                        <li>
                                                            An athlete may be managed by more than one person, especially for participation in certain types of competitions such as national competitions. The management should align with the athlete's affiliation with their province or government.
                                                        </li>
                                                    </ul>
                                                </p>
                                            </li>

                                            <li>
                                                <h4>Feature Development:</h4>
                                                <p>
                                                    The team manager feature is currently in the early stages of development. It currently
                                                    functions to manage user data and event registrations. Further development may be
                                                    undertaken in the future.
                                                </p>
                                            </li>

                                            <li>
                                                <h4>Usage of the Feature During Event Days:</h4>
                                                <p>
                                                    The team manager feature is not intended to be used abruptly during event days,
                                                    considering that its usage requires approval from the Hyperscore admin and prior review.
                                                </p>
                                            </li>

                                            <li>
                                                <h4>Future Rule Additions:</h4>
                                                <p>
                                                    Additional rules that are deemed necessary for the effective management of athletes may be added later. Such rules will be applied, and team managers will be notified accordingly.
                                                </p>
                                            </li>
                                        </ol>
                                        <p>
                                            By agreeing to these terms and conditions, you acknowledge that you have read, understood, and accepted the responsibilities outlined herein. Any violation of these terms may result in the termination of your role as a team manager OR as a User.
                                        </p>
                                        <p>
                                            These terms and conditions are subject to change at anytime needed, and any updates will be communicated to team managers promptly.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group has-validation mb-3">
                        <div class="form-check checkbox-lg">
                            <input class="form-check-input me-3" name="is_agree_with_tnc" id="is_agree_with_tnc" type="checkbox" value="1" id="checkbox-1" />
                            <label class="form-check-label d-flex fw-bold" for="is_agree_with_tnc">
                                I agree to the Team Manager Agreement Terms and Conditions above
                            </label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="input-group has-validation mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="person_in_charge_status" name="person_in_charge_status" aria-label="Floating label select example">
                                <option value="" selected>Select an Option</option>
                                <option value="coach">Coach</option>
                                <option value="parent">Parent</option>
                                <option value="team_manager">Team Manager</option>
                                <option value="sibling">Sibling</option>
                                <option value="friend">Friend</option>
                                <option value="other">Other</option>
                            </select>
                            <label for="person_in_charge_status">What is your relation with the person that you about to manage?</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <input type="hidden" name="tnc_version" value="1.0">
                    <div class="input-group has-validation mb-3">
                        <div class="form-floating">
                            <select class="form-select" id="user_manage_status" name="user_manage_status" aria-label="Floating label select example">
                                <option value="" selected>Select an Option</option>
                                <option value="kids">Kids</option>
                                <option value="student">Student</option>
                                <option value="athlete">Athlete</option>
                                <option value="brand_ambassador">Brand Ambassador</option>
                                <option value="other">Other</option>
                            </select>
                            <label for="user_manage_status">Who do you want to manage?</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="input-group has-validation mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" name="notes" id="notes" oninput="updateCharacterCount()" placeholder="Tulis catatan aplikasi anda disini" id="floatingTextarea" style="height: 100px"></textarea>
                            <label for="floatingTextarea">Catatan (Opsional)</label>
                            <div class="invalid-feedback"></div>
                            <small id="characterCount" class="form-text text-muted">0/300 characters</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>



@stop

@section('script')
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var qrcode = new QRCode("qr-container", {
        text: "{{ $user->ID_user}}",
        width: 325,
        height: 325,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });

    // on submit team-manager-application-form
    $('#team-manager-application-form').submit(function(e) {
        e.preventDefault();
        console.log('submit');
        var form = $(this);
        var method = form.attr('method');
        var url = form.attr('action');
        var data = form.serialize();
        console.log(data);

        Swal.fire({
            title: 'Are you sure?',
            text: "You will apply as Team Manager!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, apply!'
        }).then((result) => {
            if (result.isConfirmed) {
                createTeamManagerApplication(url, method, data);
            }
        });
    });

    // clear form on modal close
    $('#teamManagerApplicationModal').on('hidden.bs.modal', function () {
        $('#team-manager-application-form').trigger('reset');
        $('#response').empty();
        $('.is-invalid').removeClass('is-invalid'); // remove previous error indicators
        $('.invalid-feedback').empty(); // clear previous error messages
    });

    // hit API to create team manager application
    function createTeamManagerApplication(url, method, data) {
        action = url;
        form = data;
        successCallback = function(result) {
            console.log(result);
            $('#teamManagerApplicationModal').modal('hide');
            Swal.fire({
                title: 'Success!',
                text: "Your application has been sent! We will review your application as soon as possible.",
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            })
        }
        errorCallback = function(xhr) {
            $('#response').empty();

            $('.is-invalid').removeClass('is-invalid'); // remove previous error indicators
            $('.invalid-feedback').empty(); // clear previous error messages

            let messages = xhr.responseJSON.messages;

            let errorHtml = '<div class="alert alert-danger" role="alert">';
            Object.keys(messages).forEach(function(field) {
                errorHtml += `${messages[field][0]}<br>`;
            });
            errorHtml += '</div>';

            $('#response').html(errorHtml);
            $('#response').slideDown();

            $.each(messages, function (fieldName, errorMessages) {
                console.log(fieldName + ' ' + errorMessages[0]);

                // Get the first error message
                let errorMessage = errorMessages[0];

                if (errorMessage) {
                    // Field is invalid, add is-invalid class and display error message
                    $('#' + fieldName).addClass('is-invalid');
                    $('#' + fieldName).closest('.input-group').find('.form-check').addClass('is-invalid');
                    $('#' + fieldName).closest('.input-group').find('.invalid-feedback').html(errorMessage);

                    // If the field is a select element, add is-invalid class to it
                    $('#' + fieldName).closest('.input-group').find('select').addClass('is-invalid');
                } else {
                    // Field is valid, add is-valid class
                    $('#' + fieldName).addClass('is-valid');
                    $('#' + fieldName).closest('.input-group').find('.form-check').removeClass('is-invalid');
                    $('#' + fieldName).closest('.input-group').find('.invalid-feedback').html('');
                    $('#' + fieldName).closest('.input-group').find('select').addClass('is-valid');
                }
            });
        }
        api(action, method, form, successCallback, errorCallback)
    }

    function updateCharacterCount() {
        var textarea = document.getElementById("notes");
        var characterCountElement = document.getElementById("characterCount");

        if (textarea && characterCountElement) {
            var currentCount = textarea.value.length;
            var maxLength = 300; // Set your desired max length

            characterCountElement.textContent = currentCount + "/" + maxLength + " characters";

            // Add red border and change text color if character limit is exceeded
            if (currentCount > maxLength) {
                textarea.classList.add("is-invalid");
                // find text-muted class in characterCountElement and change it to is-invalid
                characterCountElement.classList.remove("text-muted");
                characterCountElement.classList.add("text-danger");
            } else {
                textarea.classList.remove("is-invalid");
                // Reset to default
                characterCountElement.classList.remove("text-danger");
                characterCountElement.classList.add("text-muted");
            }
        }
    }
</script>
@stop