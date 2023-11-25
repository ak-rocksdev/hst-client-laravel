@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title')Event Detail @stop

@section('description')Your Hyperscore Event Detail Page. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('meta') <meta name="robots" content="noindex, nofollow"> @stop

@section('body')
<div class="jumbotron jumbotronImage" style="background-image: url();"> </div>
<div class="hero-image-container-gradient mb-3">
    @php
        $file = '/competition/' . $event->ID_event . '/mbanner';
        $defaultFile = '/competition/default-banner.jpg';

        $bannerUrl = asset('storage' . $defaultFile);

        foreach (['png', 'jpg'] as $extension) {
            if (Storage::disk('public')->exists($file . '.' . $extension)) {
                $bannerUrl = asset('storage' . $file . '.' . $extension);
                break;
            }
        }
    @endphp
    <img class="project-main-image" src="{{ $bannerUrl }}" alt="Event Poster">
    <div class="container hero-text-container">
        <div class="hero-text">
            <h1 class="text-white fw-bold">{{ $event->name }}</h1>
            <p>
                <i class="fas fa-map-marker-alt me-2"></i>{{ $event->location }}
            </p>
            <a href="#detail" class="button button-grey">Detail<i class="fas fa-chevron-right ms-3"></i></a>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <!-- <div class="col-12 mb-2">
            <h2 class="text-uppercase fw-bold">Select Category to Check In</h2>
        </div> -->
        <div class="col-12 mb-2">
            <!-- check-in button red -->
            <button id="show-scanner" class="btn btn-red w-100 text-uppercase fw-bold mb-3" data-bs-toggle="modal" data-bs-target="#modalScanQr">
                <i class="fa-solid fa-qrcode me-3"></i> Check In
            </button>
        </div>
        @foreach ($groupedCompetitions as $sport => $competitions)
        <h3 class="fw-bold">{{ $competitions['sport'] }}</h3>
            @foreach ($competitions['data'] as $competition)
            <div class="col-12 col-lg-6 mb-4">
                <div class="check-in-container d-flex align-items-center justify-content-between flex-row bg-white gap-3">
                    <div class="d-flex flex-start gap-3 h-100 w-100 align-items-center">
                        <div class="qr-icon d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <div class="event-info ">
                            <h2 class="text-uppercase text-black fw-bold mb-0 title">{{ $competition->level }}</h2>
                            <div class="text-uppercase subtitle">
                                Total Registrant:<br />
                                <span class="fw-bold">{{ $competition->total_participant }} Person</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checked-in px-2 py-2 justify-content-center d-flex flex-end align-items-center">
                        <div class="text-uppercase fw-bold">
                            <span class="title">Checked In</span><br>
                            <span class="quantity">{{ $competition->total_attendance }}</span><br>
                            Person
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
</div>

<div class="modal fade" id="modalScanQr" tabindex="-1" aria-labelledby="modalScanQrLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-uppercase" id="modalScanQrLabel">Scan Here</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="qr-reader" style="width: 100%; margin-bottom: 3rem;"></div>
                <div id="user-qr-container" class="d-flex justify-content-center align-items-center">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script src="https://unpkg.com/html5-qrcode@2.2.1/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let token = "{{ csrf_token() }}";

    const html5QrCode = new Html5Qrcode(/* element id */ "qr-reader");
    function initScanner() {
        $('#user-qr-container').html('');
        $('#qr-reader').show();

        Html5Qrcode.getCameras().then(devices => {
            /**
             * devices would be an array of objects of type:
             * { id: "id", label: "label" }
             */
            if (devices && devices.length) {
                var cameraId = devices[0].id;
                $('.loading').hide();
                html5QrCode.start(
                    { facingMode: "environment" }, 
                    {
                        fps: 5,    // Optional, frame per seconds for qr code scanning
                        qrbox: { width: 250, height: 250 }  // Optional, if you want bounded box UI
                    },
                    (decodedText, decodedResult) => {
                        onScanSuccess(decodedText, decodedResult);
                        html5QrCode.stop()
                    },
                    (errorMessage) => {
                        // parse error, ignore it.
                    })
                    .catch((err) => {
                    // Start failed, handle it.
                });
            }
        }).catch(err => {
            // handle err
        });

        function onScanSuccess(userId, decodedResult) {
            // showLoading();
            console.log(`Scan result: ${userId}`);
            previewParticipant(token, userId);
        }
    }

    $('#show-scanner').on('click', function() {
        initScanner();
    });
    
    $('#modalScanQr').on('hidden.bs.modal', function (e) {
        html5QrCode.stop();
    });

    function previewParticipant(token, userId) {
        let action = '/api/check-in/confirmation';
        let method = 'GET';
        let data = {
            _token: token,
            ID_user: userId,
            ID_event: {{ $event->ID_event }},
        };
        let successCallback = function(response) {
            let data = response.data;

            let template = `
                <div class="card p-3 py-4">
                    <div class="text-center mb-3">
                        <img src="/assets/img/avatar400x400.jpg" alt="Profile Picture" class="avatar rounded-circle" style="width: 100px; height: 100px;">
                    </div>`;
            template +=`<div class="text-center my-3">
                        <h5 class="mt-2 mb-0">${data[0].full_name}</h5>
                        <span>(${data[0].nick_name}) - <strong>${data[0].age} YO</strong></span>
                        <div class="fst-italic mt-3">${data[0].country_id ? '<img src="/assets/img/flag/'+ data[0].country_id.toLowerCase() +'.png" height="20" class="me-2">' + data[0].country_name + '<br />' + (data[0].country_id == 'ID' ? data[0].indo_province_name + ' - ' + data[0].indo_city_name : data[0].state_name + ' - ' + data[0].city_name) : 'Origin Not Set' }</div>
                    </div>
                    <div class="text-center mb-2">
                        <h5 class="fw-bold">{{ __('messages.select_category_check_in') }}</h5>
                    </div>`;
            data.forEach(function(item) {
                template += `<input type="checkbox" class="btn-check" id="btn-check-${item.ID_competition}" name="competitions[]" value="${item.ID_competition}" autocomplete="off">
                            <label class="btn btn-outline-primary fw-bold mb-1" for="btn-check-${item.ID_competition}">${item.level}</label>`;
            });
            template += `
                <div class="buttons text-center mt-3">
                    <button class="btn btn-outline-primary px-4" data-bs-dismiss="modal">{{ __('messages.back') }}</button>
                    <button onClick="checkIn(${data[0].ID_user})" class="btn btn-primary px-4 ms-3">Check-In</button>
                </div>
            </div>`;

            $('#user-qr-container').html(template);
            
            $('#qr-reader').hide();
        };
        let errorCallback = function(response) {
            let messages = response.responseJSON.messages;
            let text = '';
            messages.forEach(function(item) {
                text += `${item}`;
            });

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: text,
            }).then(function() {
                initScanner();
            });
        };
        api(action, method, data, successCallback, errorCallback);
    }

    function checkIn(ID_user) {
        // get selected competitions
        let competitions = [];
        $('input[name="competitions[]"]:checked').each(function() {
            competitions.push($(this).val());
        });

        if (competitions.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ __('messages.response_competition_not_selected') }}",
            });
            return;
        }

        let action = '/api/check-in/set';
        let method = 'POST';
        let data = {
            _token: token,
            ID_user: ID_user,
            ID_competitions: competitions,
        };

        let successCallback = function(response) {
            $('#modalScanQr').modal('hide');
            // Swal.fire timer after 1 second and then reload the page

            let messages = response.messages;
            console.log(messages)

            // foreach messages to a list of messages
            let content = '<ul class="text-start p-0 m-0" style="list-style-type: none;">';
            messages.forEach(function(item) {
                content += `<li>${item}</li>`;
            });
            content += '</ul>';

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Check-in success!',
                showConfirmButton: true,
                html: content,
            }).then(function() {
                location.reload();
            });
        };

        let errorCallback = function(response) {
            let message = response.responseJSON.messages;
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            });
        };

        api(action, method, data, successCallback, errorCallback);
    }
</script>
@stop