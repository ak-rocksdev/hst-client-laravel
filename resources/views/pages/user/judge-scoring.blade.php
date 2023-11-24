@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title')Judge Score @stop

@section('description')Your Hyperscore Judge Event Homepage. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('meta') <meta name="robots" content="noindex, nofollow"> @stop

@section('body')
<div class="container-fluid alert-container">
    <div class="row">
        <div class="col-12">
            <div id="alert" class="alert" role="alert">
                -
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row mb-3">
        <!-- <div class="col-3 p-0">
            
        </div> -->
        <div class="col-7 p-0 d-flex flex-row">
            @php
                $judge->photoFile == null ? $file1 = '-' : $file1 = '/user/' . $judge->photoFile;
                $file2 = '/user/' . $judge->email . '.jpg';
                $defaultFile = '/assets/img/avatar400x400.jpg';
                if (Storage::disk('public')->exists($file1)) {
                    $profileUrl = asset('storage' . $file1);
                } else if (Storage::disk('public')->exists($file2)) {
                    $profileUrl = asset('storage' . $file2);
                } else{
                    $profileUrl = asset($defaultFile);
                }
            @endphp
            <img src="{{ $profileUrl }}" class="judge-avatar" alt="Judge Avatar">
            <div class="judge-container h-100 d-flex flex-column justify-content-between align-items-center">
                <div class="title py-1 px-5 w-100 h-50 d-flex align-items-center justify-content-center fs-4 fw-bold">Judge</div>
                <div class="name py-1 w-100 h-100 px-2 d-flex align-items-center justify-content-center text-center">{{ $judge->full_name }}</div>
            </div>
        </div>
        <div class="col-5 p-0">
            <div class="event-container w-100 h-100 d-flex flex-column justify-content-between align-items-center">
                <div class="title py-1 px-5 w-100 h-50 d-flex align-items-center justify-content-center fs-4 fw-bold">Event</div>
                <div class="name py-1 w-100 h-100 px-2 d-flex align-items-center justify-content-center text-center">{{ $contestant->event_name }}</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-7">
            <div class="score-container mb-3">
                <span id="score-value" class="score">...</span>
            </div>
            <!-- create contestant name container with white background, black text -->
            <div class="contestant-container bg-white text-dark rounded-4 mb-3">
                <!-- image profile -->
                @php
                    $contestant->photoFile == null ? $file1 = '-' : $file1 = '/user/' . $contestant->photoFile;
                    $file2 = '/user/' . $contestant->email . '.jpg';
                    $defaultFile = '/assets/img/avatar400x400.jpg';
                    if (Storage::disk('public')->exists($file1)) {
                        $profileUrl = asset('storage' . $file1);
                    } else if (Storage::disk('public')->exists($file2)) {
                        $profileUrl = asset('storage' . $file2);
                    } else{
                        $profileUrl = asset($defaultFile);
                    }
                @endphp
                <img src="{{ $profileUrl }}" class="user-avatar" alt="Contestant Avatar">
                <div class="contestant-detail-container">
                    <div class="contestant-name text-uppercase fw-bold">
                        {{ $contestant->full_name }} ({{ $contestant->nick_name }}) - {{ $contestant->age }} YO
                    </div>
                    <span>Stance: {{ $contestant->stance == 1 ? 'Regular' : 'Goofy' }}</span>
                </div>
            </div>
            <div class="info-container bg-white text-dark text-center rounded-4">
                <div class="games text-uppercase fw-bold">
                    {{ $contestant->round_name }} <br /> {{ $contestant->competition_level }}
                </div>
            </div>
        </div>
        <div class="col-5 text-white">
            <label for="score-range" class="form-label text-center fs-1 text-uppercase fw-bold mb-4 w-100">Set Score</label>
            <div class="vertical-slider-container">
                @for ($i = 0; $i <= 10; $i++)
                    <div class="vertical-slider-line" style="bottom: {{ ($i / 10) * 100 }}%;">
                        <span>{{ number_format($i, 2) }}</span>
                    </div>
                @endfor
                <div class="range-container">
                    <input type="range" class="form-range vertical-slider" min="0" max="10" step="0.01" value="0" id="score-range">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col text-center d-flex flex-column mt-3 gap-3">
            <div class="d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-red fw-bold btn-lg px-5" data-bs-toggle="modal" data-bs-target="#submitScoreModal">{{ __('messages.submit_score') }}</button>
                @if ($isHeadJudge)
                <button id="verify-score" type="button" class="btn btn-tosca fw-bold btn-lg px-5" data-bs-toggle="modal" data-bs-target="#verifyScoreModal">{{ __('messages.verify_score') }}</button>
                @endif
            </div>
            <!-- back to participant list button, and on the right button for the next player -->
            <div class="d-flex justify-content-between gap-2">
                <a href="/event/judge/{{ $contestant->ID_event }}" class="btn btn-secondary fs-2 fw-bold btn-lg px-5">{{ __('messages.back') }}</a>
                @php
                    $nextLink = $nextRunningId == null ? '/event/judge/'.$contestant->ID_event : '/event/judge/scoring/'.$nextRunningId->ID_running;
                    $nextText = $nextRunningId == null ?  __('messages.finish') : __('messages.next_player');
                    $className = $nextRunningId == null ? 'btn-success' : 'btn-yellow';
                @endphp
                <a href="{{ $nextLink }}" class="btn {{ $className }} fs-2 fw-bold btn-lg px-5">{{ $nextText }}</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="submitScoreModal" tabindex="-1" aria-labelledby="submitScoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="submitScoreModalLabel">Submit Score</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Get Judge score API, show all judges score and their status, and the average score if submitted -->
                <p>{{ __('messages.question_submit_score') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-red fw-bold" data-bs-dismiss="modal" onclick="submitScore()">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="verifyScoreModal" tabindex="-1" aria-labelledby="verifyScoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyScoreModalLabel">{{ __('messages.verify_score') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Get Judge score API, show all judges score and their status, and the average score if submitted -->
                <div class="average-score mb-3">
                    <div class="text-center title mb-2">Final Score:</div>
                    <div id="average-score-value" class="average-score-value p-3 text-center rounded-3 fw-bold bg-black text-white">0.00</div>
                </div>
                <p class="text-center">{{ __('messages.question_verify_score') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-red fw-bold" onclick="verifyScore()">{{ __('messages.verify_score') }}</button>
            </div>
        </div>
    </div>
@stop

@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const scoreRange = document.getElementById('score-range');
    const scoreValue = document.getElementById('score-value');
    scoreRange.addEventListener('input', function() {
        scoreValue.innerHTML = (Math.round(scoreRange.value * 100) / 100).toFixed(2);
    });

    getScoreFromCurrentJudges();
    
    function submitScore() {
        const action = '/api/score/set';
        const method = 'POST';
        const data = {
            ID_contestant: {{ $contestant->ID_contestant }},
            ID_judge: {{ $judge->ID_judge }},
            score: scoreRange.value,
            ID_games: {{ $contestant->ID_games }},
            _token: '{{ csrf_token() }}',
        };
        
        const successCallback = function(response) {
            // check if on #alert has class alert-danger, remove it
            if ($('#alert').hasClass('alert-danger')) {
                $('#alert').removeClass('alert-danger');
            }

            let messages = response.messages;
            messages.forEach(message => {
                $('#alert').addClass('alert-success').html(message);
            });
            $('.alert-container').slideDown();
            setTimeout(() => {
                $('.alert-container').slideUp();
            }, 4000);
        };
        const errorCallback = function(xhr) {
            let messages = xhr.responseJSON.messages;

            // check if on #alert has class alert-success, remove it
            if ($('#alert').hasClass('alert-success')) {
                $('#alert').removeClass('alert-success');
            }
            messages.forEach(message => {
                $('#alert').addClass('alert-danger').html(message);
            });
            $('.alert-container').slideDown();
            setTimeout(() => {
                $('.alert-container').slideUp();
            }, 4000);
        };
        api(action, method, data, successCallback, errorCallback);
    }

    function getScoreFromCurrentJudges() {
        const scoreValue = document.getElementById('score-value');
        scoreValue.innerHTML = '...';
        const action = '/api/score/get';
        const method = 'GET';
        const data = {
            ID_contestant: {{ $contestant->ID_contestant }},
            ID_games: {{ $contestant->ID_games }},
            ID_judge: {{ $judge->ID_judge }},
        };
        
        const successCallback = function(response) {
            let data = response.data;
            if(data) {
                scoreRange.value = data.score;
                scoreValue.innerHTML = (Math.round(scoreRange.value * 100) / 100).toFixed(2);
            } else {
                $('#score-value').html('0.00');
            }
        };
        const errorCallback = function(response) {
            $('#score-value').html('0.00');
        };
        api(action, method, data, successCallback, errorCallback);
    }

    // on click #verify-score button, do get current average score from all judges, and Show on verifyScoreModal, and set to id="average-score-value"
    $('#verify-score').on('click', function() {
        $('#average-score-value').html('Loading...');
        const action = '/api/score/calculate/' + {{ $contestant->ID_contestant }} + '/' + {{ $contestant->ID_games }};
        const method = 'GET';
        const data = null;
        const successCallback = function(response) {
            console.log(response)
            $('#average-score-value').html(response);
        };
        const errorCallback = function(response) {
            console.log(response);
        };
        api(action, method, data, successCallback, errorCallback);
    });

    function verifyScore() {
        showLoading();
        const action = '/api/score/verify';
        const method = 'POST';
        const data = {
            ID_contestant: {{ $contestant->ID_contestant }},
            ID_games: {{ $contestant->ID_games }},
            _token: '{{ csrf_token() }}',
        };
        
        const successCallback = function(response) {
            hideLoading();
            // remove modal
            $('#verifyScoreModal').modal('hide');
            // check if on #alert has class alert-danger, remove it
            if ($('#alert').hasClass('alert-danger')) {
                $('#alert').removeClass('alert-danger');
            }

            let messages = response.messages;
            console.log(messages)
            messages.forEach(message => {
                $('#alert').addClass('alert-success').html(message);
            });
            $('.alert-container').slideDown();
            setTimeout(() => {
                $('.alert-container').slideUp();
            }, 4000);
        };
        const errorCallback = function(xhr) {
            hideLoading();
            let messages = xhr.responseJSON.messages;
            console.log(messages)

            // check if on #alert has class alert-success, remove it
            if ($('#alert').hasClass('alert-success')) {
                $('#alert').removeClass('alert-success');
            }

            // result is like this ID_games: Array(1) 0: "Skor Sudah Di Verifikasi!"
            Object.keys(messages).forEach(key => {
                $('#alert').addClass('alert-danger').html(messages[key][0]);
            });
            $('.alert-container').slideDown();
            setTimeout(() => {
                $('.alert-container').slideUp();
            }, 4000);
        };
        api(action, method, data, successCallback, errorCallback);
    }
</script>
@stop
