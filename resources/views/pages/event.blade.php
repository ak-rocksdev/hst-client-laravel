@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Detail Event @stop

@section('description')HST (Hyper Score Technology) is an action sport management app, scoring system app, and administration in one complete system. It's a cloud based system that ensure the access on almost any internet connected devices will run well. Our services will define that to run the event can become so quick, easy, fun, and professional. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('extracss')
<style>
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
<div class="hero-image-container mb-5">
    <img class="project-main-image" src="{{ asset('assets/img/about-us-1.jpg') }}" alt="Event Poster">
    <div class="container hero-text-container">
        <div class="hero-text">
            <h1 class="text-white fw-bold">{{ $event->name }}</h1>
            <p>
                <i class="fas fa-map-marker-alt me-2"></i>
                {{ $event->location }}
            </p>
            <a href="#gallery" class="button button-grey">Detail<i class="fas fa-chevron-right ms-3"></i></a>
        </div>
    </div>
</div>
<div class="container">
    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <div class="eventInfo e_container">
                <div class="eventInfo e_header">
                    <h2>{{ $event->name }}</h2>
                    <div class="eventInfo e_subTitle">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }} | {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                            </div>
                            <div class="col-md-6 col-12">
                                <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="eventInfo e_sportAndClass">
                    <h4><i class="fas fa-award"></i>Skateboards</h4>
                    <h5>Mens Street</h5> </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="row">
                <div class="col-12">
                    <!-- <button class="btn btn-block text-center mbl-btn-ED paddingIn w-100" style="background-color:#1e2024">
                        <i class="fas fa-lock"></i> PRIVATE
                    </button>
                    <button class="btn btn-block text-center mbl-btn-ED paddingIn w-100" style="background-color:#1e2024">
                        <i class="fas fa-check"></i> REGISTERED
                    </button> -->
                    <button id="registrationButton" class="btn btn-block fs-4 fw-bold text-center w-100 mbl-btn-ED paddingIn btn-yellow btnRegistration">
                        CONTESTANT REGISTRATION
                    </button>
                    <!-- <button onclick="gotoLogin()" class="btn btn-block text-center mbl-btn-ED paddingIn btnRegistration w-100">CONTESTANT REGISTRATION Login</button> -->
                    <!-- <button data-toggle="modal" data-target="#modalRegistrationTime" class="btn btn-block text-center mbl-btn-ED paddingIn btnRegistration">CONTESTANT REGISTRATION</button> -->
                </div>
                <div class="col-12">
                    <button class="btn btn-block text-center mbl-btn-ED paddingIn btnTnc btn-dark fs-4 fw-bold w-100" data-bs-toggle="modal" data-bs-target="#myModal2">READ TERMS AND CONDITIONS</button>
                </div>
                <!-- <div class="col-12">
                    <button class="btn btn-block text-center mbl-btn-ED paddingIn btnLiveScore w-100"><i class="fas fa-circle fa-blink recordIcon"></i> WATCH LIVE SCORE</button>
                </div> -->
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row no-gutters">
        <div class="col-md-12">
            <h2 class="titleThin">
                Registered / Result
            </h2>
        </div>
    </div>
    <!--TAMPILAN CONTAINER RESULT-->
    <div class="row">
        <!-- <div class="col-12"> -->
        <!-- <div style="overflow-x: auto;"> -->
        <div class="col-sm-12" style="max-height: 300px; overflow-y: auto; margin-bottom: 25px">
            <table id="myTable" class="table table-striped table-responsive table-borderless text-uppercase">
                <tbody>
                    <tr>
                        <td><img class="img-responsive logoInsideTable" src="{{ asset('assets/img/logo_light.png') }}"></td>
                        <td colspan="6">
                            <div class="titleThin">Nama Event - Sport</div>
                            <br>
                            <div class="titleContent" style="font-size: 24px;margin-top: -1em;">CLASS : Mens Street</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%">NO.</td>
                        <td width="45%">NAME</td>
                        <td width="40%">ORIGIN</td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Nama</td>
                        <td>Provinsi, Kota <i style='color: red;'>(Update your Profile)</i></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 form-inline tableHeadTitle">
                    <div class="logoContainer"> <img class="img-responsive logoInsideTable" src="{{ asset('assets/img/logo_light.png') }}"> </div>
                    <div>
                        <div class="titleThin">Skateboard</div>
                        <div class="titleThin">Nama Event</div>
                        <div class="titleContent" style="font-size: 15px;">CLASS : Women Street</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="outerTableContainer">
                    <div class="tableContainer columnWidth" style="width: 50%;">
                        <table id="myTable" class="table table-striped table-responsive table-borderless text-uppercase">
                            <tbody>
                                <tr>
                                    <th class="titleContent text-center">NO.</th>
                                    <th class="titleContent text-center">Name</th>
                                </tr>
                                <tr>
                                    <td class="titleContent numberIncrement text-center">1.</td>
                                    <td class="titleContent nameList" st>Nama Player</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tableContainer" style="width: 50%;">
                        <!-- NOTE -->
                        <div class="innerTableContainer">
                            <table id="myTable" class="table table-striped table-responsive table-borderless text-uppercase tableOverflow">
                                <div class="tableShadowLeft"></div>
                                <tbody>
                                    <tr>
                                        <th class="titleContent text-center">Final</th>
                                        <th class="titleContent text-center">Point</th>
                                    </tr>
                                    <tr>
                                        <td class="titleContent text-center">0.00</td>
                                        <td class="titleContent text-center point">25</td>
                                    </tr>
                                </tbody>
                                <div class="tableShadowRight"></div>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--END CONTAINER RESULT -->

</div>

<!-- <div id="registrationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="agreement">
                    <i class="fas fa-exclamation-triangle mb-dngr-R2" style="color: #bf1e2d;font-size: 60pt; text-align: center; display: block;"></i>
                    <h2 class="titleThin mb-ttlMdl-R2 text-center" style="color: #000;">Baca dan perhatikan</h2>
                    <h2 class="mb-ctnMdl-R2" style="color: #000 !important; display:inline-block; font-size: 150%;">
                        Term and Condition
                    </h2>
                    <button type="button" class="btn center-block btn-block mb-btnCls-R2 mx-auto" data-bs-dismiss="modal" style="width: 50% !important;">Batal</button>
                    <button id="setuju" type="button" class="btn center-block btn-block mb-btnCls-R2 mx-auto" style="width: 50% !important;">Setuju</button>
                </div>

                <div class="row chooseCategory" style="display:none;">
                    <div class="col-md-12 mb-5">
                        <span class="fas fa-exclamation-triangle mb-dngr-R2" style="color: #bf1e2d;font-size: 60pt; text-align: center; display: block;"></span>
                        <h2 class="titleThin text-center" style="color: #000;">Pilih Kategori Pertandingan</h2>
                    </div>
                    <div class="col text-center">
                        <button type="button" onClick="register_contest(1, 2)" class="btn btn-dark fs-5 fw-bold w-100 btn-level" data-bs-dismiss="modal">
                            Skateboard - Mens Street
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

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


                                            <td class="titleContent text-center ">0.00</td>
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
        <!--END CONTAINER RESULT -->
    </div>

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
        let action = '/api/confirm-event-on-register';
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
                        let action = '/api/register-event';
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
                                    title: '<strong>Success!</strong>',
                                    icon: 'success',
                                    html: '<div class="titleContent" style="font-size: 24px;">' +
                                        'You have successfully registered for the event</div>',
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
                                title: '<strong>Failed!</strong>',
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

    function register_contest(idCompetition, player_name, event_name, category_name) {
        Swal.fire({
            title: '<strong>Check Your Data:</strong>',
            icon: 'info',
            html: '<div class="titleContent" style="font-size: 24px;">' +
                'Contestant:</div>' + player_name + ' </br></br>' + 
                '<div class="titleContent" style="font-size: 24px;">Event Name:</div>' + event_name + '</br></br>' + 
                '<div class="titleContent" style="font-size: 24px;">Category: <span class="badge text-bg-success">' + category_name + '</span></div>',
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> Continue',
            confirmButtonAriaLabel: 'Thumbs up, great!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.value) {
                $('.loading-container').css("display", "flex");
                const idCompetition = $("input[name='competitionLevel[]']:checked").map(function () {
                    return this.value;
                }).get();
                if(0) { // if Direct Register (perlu rumus)
                    $(function() {
                        $.ajax({
                            url: "",
                            type: "POST",
                            dataType: "json",
                            data: "ID_user=" + id_user + "&competition=" + idCompetition,
                            success: function(res) {
                                console.log(res)
                                $('.loading-container').css(" display", "none");
                                if(Number(res.status) === 1) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'You Are now Registered!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(result => {
                                        if(result.isConfirmed) {
                                            window.location = "";
                                        } else {
                                            window.location = "";
                                        }
                                    });
                                } else if(Number(res.status) === 3) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Registrant is Reach the Maximum Limit!',
                                        text: 'No more space for this category',
                                    });
                                } else if(Number(res.status) === 5) {
                                    Swal.fire({
                                        confirmButtonText: 'Edit Profile',
                                        showCloseButton: true,
                                        showCancelButton: true,
                                        icon: 'error',
                                        title: 'Complete Your Profile!',
                                        text: 'Profil Anda Belum Lengkap, Harap lengkapi sebelum melanjutkan!',
                                    }).then((result) => {
                                        if(result.value) {
                                            window.location.href = `kontestan/editprofile`
                                        }
                                    });
                                } else if(Number(res.status) === 4) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'You Are on This Event!',
                                        text: 'User already registered',
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed!',
                                        text: 'Registration Failed, Contact Administrator',
                                    });
                                }
                            },
                            complete: function(response) {
                                console.log(response)
                            }
                        })
                    })
                } else {
                    window.location = "";
                }
            }
        })
    }
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
        if (signedIn) {
            openRegistrationModal();
        } else {
            gotoLogin();
        }
    });
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
@stop