@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Detail Event @stop

@section('description')HST (Hyper Score Technology) is an action sport management app, scoring system app, and administration in one complete system. It's a cloud based system that ensure the access on almost any internet connected devices will run well. Our services will define that to run the event can become so quick, easy, fun, and professional. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('extracss')
<style>
    .btn-group {
        border-radius: 0.375rem !important;
    }

    .table-container {
        overflow-y: auto;
        max-height: 300px;
    }

    .table-container thead {
        position: sticky;
        top: 0;
    }

    .mbl-btn-ED {
        border-radius: 0 !important;
        margin-bottom: 15px;
    }

    .loading-container {
        font-size: 70px;
        color: #bf1e2d;
        position: fixed;
        z-index: 999;
        overflow: show;
        margin: auto;
        display: none;
        width: 100%;
        height: 100%;
    }

    .loading-container:before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.5);
    }

    .loadingIcon {
        position: fixed;
        top: 50%;
        left: 50%;
    }

    @media (max-width: 768px) {
        body {
            background: -webkit-linear-gradient(rgba(76, 76, 76) 10%, rgba(30, 32, 36) 40%);
            background: -o-linear-gradient(rgba(76, 76, 76) 10%, rgba(30, 32, 36) 40%);
            background: linear-gradient(rgba(76, 76, 76) 10%, rgba(30, 32, 36) 40%);
            background: -ms-linear-gradient(rgba(76, 76, 76) 10%, rgba(30, 32, 36) 40%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(10%, rgba(76, 76, 76)), color-stop(40%, rgba(30, 32, 36)));
            background-image: linear-gradient(to bottom, #4c4c4c 10%, #1e2024 40%);
        }

        .mbl-titleThin-ED {
            font-size: 16pt;
        }

        .mbl-titleContent-ED {
            font-size: 16pt;
        }

        .Num {
            font-size: 36pt !important;
        }

        .Month {
            font-size: 14pt !important;
        }
    }
</style>
@stop

@section('body')
<div class="jumbotron jumbotronImage" style="background-image: url();"> </div>
<div class="hero-image-container-gradient">
    @php
        $file1 = '/competition/' . $event->ID_event . '/mbanner.png';
        $file2 = '/competition/' . $event->ID_event . '/mbanner.jpg';
        $defaultFile = '/competition/default-banner.jpg';

        if (Storage::disk('public')->exists($file1)) {
            $bannerUrl = asset('storage' . $file1);
        } else if (Storage::disk('public')->exists($file2)) {
            $bannerUrl = asset('storage' . $file2);
        } else{
            $bannerUrl = asset('storage' . $defaultFile);
        }
    @endphp
    <img class="project-main-image" src="{{ $bannerUrl }}" alt="Event Poster">
    <div class="container hero-text-container">
        <div class="hero-text">
            <h1 class="text-white fw-bold">{{ $event->name }}</h1>
            <p>
                <i class="fas fa-map-marker-alt me-2"></i>
                {{ $event->location }}
            </p>
            <a href="#detail" class="button button-grey">Detail<i class="fas fa-chevron-right ms-3"></i></a>
        </div>
    </div>
</div>
<div id="detail" class="container py-5">
    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <div class="eventInfo e_container">
                <div class="eventInfo e_header">
                    <h2>{{ $event->name }}</h2>
                    <div class="eventInfo e_subTitle">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <i class="far fa-clock me-2"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }} | {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                            </div>
                            <div class="col-md-6 col-12">
                                <i class="fas fa-map-marker-alt me-2"></i> {{ $event->location }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="eventInfo e_sportAndClass">
                    <div class="row">
                    @foreach ($groupedCompetitions as $groupedCompetition)
                        <div class="col-6">
                            <h4>
                                <i class="fas fa-award"></i>{{ $groupedCompetition['sports'] }}
                            </h4>
                            @foreach ($groupedCompetition['levels'] as $level)
                            <h5>{{ $level }}</h5>
                            @endforeach
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="row">
                <div class="col-12">
                    <!-- <button class="btn btn-block text-center mbl-btn-ED paddingIn w-100" style="background-color:#1e2024">
                        <i class="fas fa-lock"></i> PRIVATE
                    </button> -->
                    <button id="registrationButton" class="btn btn-block fs-4 fw-bold text-center w-100 mbl-btn-ED paddingIn btn-yellow text-uppercase {{ $isRegistrationOpen ? '' : 'disabled' }}">
                        {{ $isRegistrationOpen ? __('messages.register_to_this_event') : __('messages.registration_closed') }}
                    </button>
                </div>
                <div class="col-12">
                    <button class="btn btn-block text-center mbl-btn-ED paddingIn btnTnc btn-dark fs-4 fw-bold w-100 text-uppercase" data-bs-toggle="modal" data-bs-target="#myModal2">
                        {{ __('messages.read_terms_and_conditions') }}
                    </button>
                </div>
                <!-- <div class="col-12">
                    <button class="btn btn-block text-center mbl-btn-ED paddingIn btnLiveScore w-100"><i class="fas fa-circle fa-blink recordIcon"></i> WATCH LIVE SCORE</button>
                </div> -->
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-uppercase">
                {{ __('messages.registered_contestant') }}
            </h2>
        </div>
    </div>
    @if($competitions->count() != 0)
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="competition" class="form-label me-3 text-white fw-bold text-uppercase">Filter</label>
            <div id="filter-btn-group" class="btn-group btn-group-container" role="group" aria-label="Radio toggle button group">
                @foreach ($competitions as $competition)
                <input type="radio" class="btn-check" name="competition" id="competition-{{ $competition->ID_competition }}" autocomplete="off" value="{{ $competition->ID_competition }}" {{ $loop->first ? 'checked' : '' }}>
                <label class="btn btn-white fw-bold" for="competition-{{ $competition->ID_competition }}">{{ $competition->sports }} - {{ $competition->level }}</label>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <!-- Tabel Registered Contestant -->
    <!-- NOTE: $contestants if not set yet, to be fixed later when working on Event Creation -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="table-header-gradient d-flex flex-row align-items-center p-3">
                <img class="me-3" src="{{ asset('assets/img/logo_light.png') }}">
                <div class="d-flex flex-column table-title-container">
                    <div id="event-sport" class="fw-bold fs-3">{{ $contestants[0]->event_name }} - {{ $contestants[0]->sport_name }}</div>
                    <div class="fw-normal">{{ __('messages.category') }} : <span id="competition-name" ></span></div>
                </div>
            </div>
            <div class="table-container">
                <table id="myTable" class="table table-dark table-striped table-borderless text-uppercase">
                    <thead>
                        <tr colspan="3">
                            <th width="15%"><span class="fw-bold">NO.</span></th>
                            <th width="45%"><span class="fw-bold">{{ __('messages.name') }}</span></th>
                            <th width="40%"><span class="fw-bold">{{ __('messages.origin') }}</span></th>
                        </tr>
                    </thead>
                    <tbody id="contestant-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row pt-5 pb-3">
        <div class="col-md-12">
            <h2 class="fw-bold text-uppercase">
                {{ __('messages.contest_result') }}
            </h2>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="competition" class="form-label me-3 text-white fw-bold text-uppercase">Filter</label>
            <div id="filter-result-btn-group" class="btn-group btn-group-container" role="group" aria-label="Radio toggle button group">
                @foreach ($competitions as $competition)
                <input type="radio" class="btn-check" name="result" id="result-{{ $competition->ID_competition }}" autocomplete="off" value="{{ $competition->ID_competition }}" {{ $loop->first ? 'checked' : '' }}>
                <label class="btn btn-white fw-bold" for="result-{{ $competition->ID_competition }}">{{ $competition->sports }} - {{ $competition->level }}</label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-sm-12">
            <div class="table-header-gradient d-flex flex-row align-items-center p-3">
                <img class="me-3" src="{{ asset('assets/img/logo_light.png') }}">
                <div class="d-flex flex-column table-title-container">
                    <div id="result-event-sport" class="fw-bold fs-3"></div>
                    <div class="fw-normal">{{ __('messages.category') }} : <span id="result-competition-name" ></span></div>
                </div>
            </div>
            <div class="outerTableContainer table-responsive-wrapper">
                <div>
                    <table id="result-table" class="table table-dark table-striped table-responsive table-borderless text-uppercase">
                        <thead id="result-table-head">
                            <tr>

                            </tr>
                        </thead>
                        <!-- <div class="tableShadowLeft"></div> -->
                        <tbody id="result-table-body">
                            <!-- <tr>
                                <td class="titleContent numberIncrement text-center">1</td>
                                <td class="titleContent nameList">Nama</td>
                            </tr> -->
                        </tbody>
                        <!-- <div class="tableShadowRight"></div> -->
                    </table>
                </div>
                
            </div>
        </div>
    </div>
    <!--END CONTAINER RESULT -->

    <div id="myModal2" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-body tncModal ">
                    <!-- <h3 class="text-center text-black">Terms and Conditions</h3> -->
                    {!! $event->tnc !!}
                    <div class="btn btn-readmore" data-bs-dismiss="modal">CLOSE</div>
                </div>
            </div>
        </div>
    </div>



    <div id="modalRegistrationTime" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body text-center tncModal">
                    <h2>Registration open at</h2> 12 Juni - 14 Juni 2023
                    <div class="btn btn-readmore" data-toggle="modal" data-target="#modalRegistrationTime">CLOSE</div>
                </div>
            </div>
        </div>
    </div>


                                            <!-- <td class="titleContent text-center ">0.00</td>
                                            <td class="titleContent text-center point ">25</td>
                                        </tr>
                                    </tbody>
                                    <div class="tableShadowRight "></div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div id="registrationModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="agreement">
                        <div class="modal-header">
                            <i class="fas fa-exclamation-triangle mb-dngr-R2" style="color: #bf1e2d;font-size: 60pt; text-align: center; display: block;"></i>
                            <h2 class="mb-0 text-uppercase fw-bold text-center" style="color: #000;">Baca dan perhatikan</h2>
                        </div>
                        <div class="mb-ctnMdl-R2 py-5">{!! $event->tnc !!}</div>
                        
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-red fs-5 fw-bold" data-bs-dismiss="modal">Batal</button>
                            <button id="chooseCategory" type="button" class="btn btn-dark fs-5 fw-bold">Setuju</button>
                        </div>
                    </div>
                    <div class="row chooseCategory" style="display:none;">
                        <div class="col-md-12">
                            <span class="fas fa-exclamation-triangle mb-dngr-R2" style="color: #bf1e2d;font-size: 60pt; text-align: center; display: block;"></span>
                            <h2 class="text-center text-black fw-bold">Pilih Kategori Pertandingan</h2>
                            <hr>
                            <div id="max-join-competition" class="bd-callout bd-callout-info" style="display: none;">
                                Maksimal kompetisi yang bisa diikuti: <strong>{{ $event->max_join_competition }}</strong>
                            </div>
                        </div>
                        <div class="col-12 text-center py-4">
                            @foreach ($sports as $sport)
                                <h3 class="fw-bold text-uppercase text-black">{{ $sport->name }}</h3>
                                <div class="mb-5">
                                    <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                                    @foreach ($competitions as $key => $competition)
                                        @if ($competition->sport == $sport->ID_sport)
                                        <input type="checkbox" class="btn-check" name="competitionLevel[]" data-max-join="{{ $event->max_join_competition }}" value="{{ $competition->ID_competition }}" id="btncheck{{ $key }}" autocomplete="off">
                                        <label class="btn btn-outline-primary" for="btncheck{{ $key }}">{{ $competition->level }}</label>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button id="backToAgreement" type="button" class="btn btn-red fs-5 fw-bold">Prev</button>
                            <button id="submit" type="button" class="btn btn-dark fs-5 fw-bold">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body tncModal">
                <?php echo $event['tnc']; ?>
                    <div class="btn btn-readmore" data-toggle="modal" data-target="#myModal2">CLOSE</div>
            </div>
        </div>
    </div>
</div>
<div id="modalRegistrationTime" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center tncModal">
                <h2>Registration open at</h2>
                <?php echo date("d M Y H:i", strtotime($event['start_registration'])) . " - " . date("d M Y H:i", strtotime($event['end_registration'])); ?>
                    <div class="btn btn-readmore" data-toggle="modal" data-target="#modalRegistrationTime">CLOSE</div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // function shortenText() {
    //     var str = document.querySelectorAll('.nameList');
    //     for(i = 0; i < str.length; i++) {
    //         if(str[i].innerText.length > 10) {
    //             strNew = str[i].innerText.substring(0, 11) + '..';
    //             document.querySelectorAll('.nameList')[i].innerText = strNew;
    //         }
    //     }
    // }

    // function shortenTextTab() {
    //     var str = document.querySelectorAll('.nameList');
    //     for(i = 0; i <br str.length; i++) {
    //         if(str[i].innerText.length > 12) {
    //             strNew = str[i].innerText.substring(0, 13) + '..';
    //             document.querySelectorAll('.nameList')[i].innerText = strNew;
    //         }
    //     }
    // }
    $(document).ready(function() {
        $("#scoreList").nextAll("#scoreList").hide();
        $("#scoreList").hide();
        $(".detailScore").click(function(e) {
            e.preventDefault();
            $('.' + this.id).slideToggle("fast");
            var s = $('#' + this.id).children();
            s.children("#showMore").toggleClass("open");
            var color = $('#' + this.id).css("background-color");
            $('.' + this.id).css("background-color", color);
        });

        $('input[type="checkbox"]').on('click', function() {
            var maxJoin = $(this).data('max-join');
            var checkedBoxes = $('input[type="checkbox"]:checked');

            if (checkedBoxes.length >= maxJoin) {
                $('#max-join-competition').slideDown();
                // Disable unchecked checkboxes if the limit is reached
                $('input[type="checkbox"]').not(':checked').prop('disabled', true);
            } else {
                $('#max-join-competition').slideUp();
                // Enable all checkboxes if the limit is not reached
                $('input[type="checkbox"]').prop('disabled', false);
            }
        });
    });

    let userId =  {{ $signed_in ? $signed_in->ID_user : '' }}
    // get current user logged in
    $('#submit').click(function() {
        showLoading();
        let idCompetition = $("input[name='competitionLevel[]']:checked").map(function() {
            return this.value;
        }).get();
        
        // register_contest(idCompetition, player_name, event_name, category_name)
        // Hit Api Event, Competition, User and Get Info to show on SweetAlert
        let action = '/api/event/confirmation';
        let method = 'GET';
        data = {
            userId: userId,
            eventId: {{ $event->ID_event }},
            competitionId: idCompetition
        };
        successCallback = function(response) {
            hideLoading();
            if(response) {
                let data = response.data;
                let competitions = ''
                data.competitions.forEach(competition => {
                    competitions += '</br><span class="badge text-bg-success">' + competition.sport_name + ' - ' + competition.level + '</span>';
                });
                Swal.fire({
                    title: '<strong>Check Your Data:</strong>',
                    icon: 'info',
                    html: '<div class="titleContent" style="font-size: 24px;">' +
                        '{{ __("messages.contestant") }}:</div>' + data.userRegister.full_name + '</br></br>' + 
                        '<div class="titleContent" style="font-size: 24px;">{{ __("messages.event_name") }}:</div>' + data.event.name + '</br></br>' + 
                        '<div class="titleContent" style="font-size: 24px;">{{ __("messages.selected_category") }}:' + competitions + '</div>',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: '<i class="fas fa-thumbs-up"></i> Continue',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if(result.value) {
                        showLoading();
                        // Hit Api Register
                        let action = '/api/event/register';
                        let method = 'POST';
                        data = {
                            // add csrf
                            _token: '{{ csrf_token() }}',
                            ID_user: userId,
                            ID_event: {{ $event->ID_event }},
                            ID_competition: idCompetition
                        };
                        successCallback = function(response) {
                            hideLoading();
                            if(response) {
                                Swal.fire({
                                    title: '<strong>{{ __("messages.response_success") }}</strong>',
                                    icon: 'success',
                                    html: '<div>{{ __("messages.response_register_event_success") }}</div>',
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    focusConfirm: false,
                                    confirmButtonText: '<i class="fas fa-thumbs-up"></i> Continue',
                                    confirmButtonAriaLabel: 'Thumbs up, great!',
                                }).then((result) => {
                                    return location.reload();
                                })
                            }
                        };
                        errorCallback = function (xhr) {
                            hideLoading();
                            let response = xhr.responseJSON.messages;
                            let messages = ''
                            Object.keys(response).forEach(function(field) {
                                messages += `<span class="fs-5 text-black"> ${response[field][0]} </span>`;
                            });
                            Swal.fire({
                                title: '<strong>{{ __("messages.response_failed") }}</strong>',
                                icon: 'error',
                                html: '<div>' + messages + '</div>',
                                showCloseButton: true,
                                showCancelButton: false,
                                focusConfirm: false,
                                confirmButtonText: '<i class="fas fa-times me-2"></i> {{ __("messages.close") }}',
                                confirmButtonAriaLabel: 'Thumbs up, great!',
                            }).then((result) => {
                                $('.modal').modal('hide');
                            })
                        };
                        api(action, method, data, successCallback, errorCallback);
                    }
                })
            }
        };
        errorCallback = function (xhr) {
            console.log('error', xhr.responseJSON)
        };
        api(action, method, data, successCallback, errorCallback);
    })

    var screenWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    if(screenWidth <= 991 && screenWidth >= 769) {
        shortenTextTab();
    } else if(screenWidth < 768 && screenWidth > 471) {
        shortenText();
        var headWidth = $(" .tableHeadTitle ").width();
        $(".innerTableContainer ").css({
            "width ": "calc( " + headWidth + "px - 76%) "
        });
    } else if(screenWidth <= 470) {
        shortenText();
        var headWidth = $(".tableHeadTitle ").width();
        $(".innerTableContainer ").css({
            "width ": "calc( " + headWidth + "px - 55%) "
        });
    }

    function gotoLogin() {
        window.location.href = "/login";
    }

    function openRegistrationModal() {
        $('#registrationModal').modal('show');
    }

    let signedIn = {{ Auth::check() ? 'true' : 'false' }};

    document.getElementById("registrationButton").addEventListener("click", function () {
        if (!signedIn) {
            gotoLogin();
            return;
        } else {
            checkRegistrationStatus();
        }
    });

    function checkRegistrationStatus() {
        showLoading();
        let action = '/api/event/check-status-by-user';
        let method = 'GET';
        data = {
            ID_event: {{ $event->ID_event }},
        };
        successCallback = function(response) {
            hideLoading();
            if(response) {
                let data = response.data;
                // Assuming the API response contains 'data' and 'remainingSlots' properties
                if (data.user_count == null || data.user_count == 0) {
                    // User is not registered, proceed with registration
                    openRegistrationModal();
                } else if (data.remaining_slots > 0) {
                    // User is registered but has more slots available, proceed with registration
                    openRegistrationModal();
                } else {
                    // User is registered and has reached the maximum number of registrations
                    Swal.fire({
                        title: '<strong>{{ __("messages.response_failed") }}</strong>',
                        icon: 'warning',
                        html: '<div>{{ __("messages.response_event_already_registered") }}</div>',
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: '<i class="fas fa-times me-2"></i> {{ __("messages.close") }}',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                    }).then((result) => {
                        $('.modal').modal('hide');
                    })
                }
            }
        };
        errorCallback = function (xhr) {
            hideLoading();
            let response = xhr.responseJSON.messages;
            let messages = ''
            Object.keys(response).forEach(function(field) {
                messages += `<span class="fs-5 text-black"> ${response[field]} </span>`;
            });
            Swal.fire({
                title: '<strong>{{ __("messages.response_failed") }}</strong>',
                icon: 'error',
                html: '<div>' + messages + '</div>',
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: '<i class="fas fa-times me-2"></i> {{ __("messages.close") }}',
                confirmButtonAriaLabel: 'Thumbs up, great!',
            }).then((result) => {
                $('.modal').modal('hide');
            })
            console.log('error', xhr.responseJSON)
        };
        api(action, method, data, successCallback, errorCallback);
    }
</script>

<script type="text/javascript ">
    $(document).ready(function() {
        $('#chooseCategory').click(function() {
            $('.agreement').fadeOut().css('display', 'none');
            $('.chooseCategory').fadeIn().css('display', 'block');
        })

        $('#backToAgreement').click(function() {
            $('.chooseCategory').fadeOut().css('display', 'none');
            $('.agreement').fadeIn().css('display', 'block');
        })

        // on all modal close, trigger $('#backToAgreement').click()
        $('.modal').on('hidden.bs.modal', function() {
            $('#backToAgreement').click();
        })
    });
</script>

<script>
    showLoading();
    $(document).ready(function() {
        // Function to populate the table with contestant data
        function populateTable(contestants, competition) {
            var tableBody = $('#contestant-table-body');
            tableBody.empty(); // Clear existing table rows

            updateEventAndCompetitionName(competition);
            $.each(contestants, function(key, contestant) {
                var row = $('<tr>');
                row.append('<td>' + (key + 1) + '.</td>');
                row.append('<td>' + contestant.full_name + '</td>');
                var origin = '';
                if (contestant.indo_province_name) {
                    origin += contestant.indo_province_name + ', ';
                }
                if (contestant.indo_city_name) {
                    origin += contestant.indo_city_name + ', ';
                }
                if (contestant.state_name) {
                    origin += contestant.state_name + ', ';
                }
                origin += contestant.country_name ? contestant.country_name : '(Update your Profile)';
                row.append('<td>' + origin + '</td>');
                tableBody.append(row);
                hideLoading();
            });
        }

        function updateEventAndCompetitionName(competition) {
            $('#event-sport').text(competition.event_name + ' - ' + competition.sport_name);
            $('#competition-name').text(competition.level);
        }

        // Function to load the table data based on the selected competition
        function loadTableData(competitionId) {
            $.ajax({
                url: '/api/event/registered-contestant', // Replace with your API endpoint URL
                method: 'GET',
                data: { 
                    ID_competition: competitionId,
                    ID_event: {{ $event->ID_event }},
                }, // Pass the selected competition ID as a parameter
                success: function(response) {
                    var contestants = response.data.contestants;
                    var competition = response.data.competition;
                    populateTable(contestants, competition);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        // Add event listener to the filter button group
        $('#filter-btn-group input[type="radio"]').on('change', function() {
            showLoading();
            var selectedCompetitionId = $(this).val();
            loadTableData(selectedCompetitionId);
        });

        // Load table data with the default selected competition
        var defaultCompetitionId = $('#filter-btn-group input[type="radio"]:checked').val();
        loadTableData(defaultCompetitionId);
    });
</script>

<script>
    $('#filter-result-btn-group input[type="radio"]').on('change', function() {
        showLoading();
        console.log('test')
        var selectedCompetitionId = $(this).val();
        loadResult(selectedCompetitionId); // New function for loading "Finals" section data
    });

    function loadResult(competitionId) {
        $.ajax({
            url: '/api/event/result', // Replace with your API endpoint URL for loading "Finals" section data
            method: 'GET',
            data: {
                ID_competition: competitionId,
            }, // Pass the selected competition ID as a parameter
            success: function(response) {
                hideLoading();
                // Assuming your API returns the "Finals" section data in response.data.finalsData
                var finalsData = response.data;
                populateResultTable(finalsData);
            },
            error: function(xhr, status, error) {
                hideLoading();
                console.log(error);
            }
        });
    }

    function fillScoreWithZero(scores, maxGames) {
        var filledScores = scores.split(',');
        while (filledScores.length < maxGames) {
            filledScores.push('0.00');
        }
        return filledScores;
    }

    function populateResultTable(response) {
        //head table
        var tableHead = $('#result-table-head');
        var tableBody = $('#result-table-body');
        tableHead.empty();
        tableBody.empty();

        // Append the table header
        var headerRow = $('<tr>');
        headerRow.append('<th class="titleContent text-center">No</th>');
        headerRow.append('<th class="titleContent">Name</th>');

        // Append the score header for each game (Final) with index
        let gameCount = 0;
        
        $.each(response.summedScores, function(index, contestant) {
            headerRow.append('<th class="titleContent text-center">' + 'F'+ (index + 1) + '</th>');
            if (index + 1 == Object.keys(contestant.score.split(',')).length) {
                return false;
            }
            index++;
        });
        headerRow.append('<th class="titleContent text-center">Final Score</th>');
        $.each(response.summedScores, function(index, contestant) {
            let row = $('<tr>');
            row.append('<td class="titleContent numberIncrement text-center">' + (index + 1) + '.' + '</td>');
            row.append('<td class="titleContent nameList">' + contestant.full_name + '</td>');
            // Append score data for each game (Final) to the row
            // $.each(contestant.score.split(','), function(index, score) {
            //     var scoreCell = $('<td class="titleContent text-center">');
            //     scoreCell.text(score);
            //     row.append(scoreCell);
            // });
            var maxGames = headerRow.find('th').length - 3; // Subtracting 2 to account for the "NO." and "Name" columns
            var scores = fillScoreWithZero(contestant.score, maxGames);

            // Append score data for each game (Final) to the row
            $.each(scores, function(index, score) {
                var scoreCell = $('<td class="titleContent text-center">');
                scoreCell.text(score);
                row.append(scoreCell);
            });
            var scoreCell = $('<td class="titleContent text-center">');
            scoreCell.text(contestant.final_score);
            row.append(scoreCell);
            row.append('</tr>');
            tableHead.append(headerRow);
            tableBody.append(row);
        });

        
            
        // $.each(response.scoresByFinal, function(gameID, scoreData) {
        //     headerRow.append('<th class="titleContent text-center">' + 'F'+ (gameCount + 1) + '</th>');
        //     gameCount++;
        //     if (gameCount === Object.keys(scoreData).length) {
        //         return false;
        //     }
        // });

        // // set value for id="result-event-sport"
        $('#result-event-sport').text(response.competition.event_name + ' - ' + response.competition.sport_name);
        $('#result-competition-name').text(response.competition.level);
        // // Loop through the finals data and populate the table
        // $.each(response.contestants, function(index, contestant) {
        //     var row = $('<tr>');
        //     row.append('<td class="titleContent numberIncrement text-center">' + (index + 1) + '.' + '</td>');
        //     row.append('<td class="titleContent nameList">' + response.contestantNames[contestant.ID_user] + '</td>');

        //     // Append score data for each game (Final) to the row
        //     $.each(response.scoresByFinal[contestant.ID_contestant], function(gameID, scoreData) {
        //         var scoreCell = $('<td class="titleContent text-center">');

        //         // if highlight is true, add class="text-primary" to the score
        //         if (scoreData) {
        //             scoreCell.text(scoreData.score);
        //             if (scoreData.highlight) {
        //                 scoreCell.addClass('text-primary');
        //             }
        //         } else {
        //             scoreCell.text('N/A');
        //         }

        //         row.append(scoreCell);
        //     });
        //     row.append('</tr>');

        //     tableBody.append(row);
        // });
        // headerRow.append('</tr>');

        // tableHead.append(headerRow);
    }

    var defaultCompetitionId = $('#filter-result-btn-group input[type="radio"]:checked').val();
    loadResult(defaultCompetitionId);
</script>
@stop