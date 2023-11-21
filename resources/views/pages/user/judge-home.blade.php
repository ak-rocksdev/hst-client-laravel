@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title')Judge Homepage @stop

@section('description')Your Hyperscore Judge Event Homepage. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop


@section('body')
<div class="jumbotron jumbotronImage" style="background-image: url();"> </div>
<div class="hero-image-container-gradient mb-5">
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
    @if($competitions->count() != 0)
    <div class="row">
        <div class="col-12 text-white">
            <h2 class="fw-bold mb-4" id="detail">{{ $competitions[0]->sports }} Judging</h2>
            <!-- class list (sudah)</br>
            if clicked, show the participant</br>
            if participant is grouped, show by the group</br></br> 

            Tambah Run -->
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <label for="competition" class="form-label me-3 text-white fw-bold text-uppercase">Select Category</label>
            <div id="filter-btn-group" class="btn-group btn-group-container" role="group" aria-label="Radio toggle button group">
                <input type="radio" class="btn-check" name="competition" id="competition-0" autocomplete="off" value="0" checked>
                <label class="btn btn-white fw-bold" for="competition-0">All</label>
                @foreach ($competitions as $competition)
                <input type="radio" class="btn-check" name="competition" id="competition-{{ $competition->ID_competition }}" autocomplete="off" value="{{ $competition->ID_competition }}">
                <label class="btn btn-white fw-bold" for="competition-{{ $competition->ID_competition }}">{{ $competition->level }}</label>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div id="round-filter-container" style="display: none;" class="row mb-4">
        <div class="col-md-12">
            <label for="competition" class="form-label me-3 text-white fw-bold text-uppercase">Select Round</label>
            <div id="round-options" class="btn-group btn-group-container" role="group" aria-label="Radio toggle button group">
            </div>
        </div>
    </div>

    <div id="run-filter-container" style="display: none;" class="row mb-4">
        <div class="col-md-12">
            <label for="competition" class="form-label me-3 text-white fw-bold text-uppercase">Select Attempt</label>
            <div id="run-options" class="btn-group btn-group-container" role="group" aria-label="Radio toggle button group">
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="table-header-gradient d-flex flex-row align-items-center p-3">
                <img class="me-3" src="{{ asset('assets/img/logo_light.png') }}">
                <div class="d-flex flex-column table-title-container">
                    <div id="event-sport" class="fw-bold fs-3"></div>
                    <div class="fw-normal">{{ __('messages.category') }} : <span id="competition-name" ></span></div>
                </div>
            </div>
            <div class="table-container">
                <table id="participant-table" class="table table-dark table-striped table-borderless text-uppercase">
                    <thead>
                        <tr colspan="3">
                            <th class="text-center" width="10%"><span class="fw-bold">NO.</span></th>
                            <th width="50%">
                                <span class="fw-bold">
                                    {{ __('messages.name') }}
                                </span>
                            </th>
                            <th width="20%"><span class="fw-bold">{{ __('messages.origin') }}</span></th>
                            <th width="20%" class="text-center"><span class="fw-bold">Action</span></th>
                        </tr>
                    </thead>
                    <tbody id="participant-table-body">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('script')
<script>
    // if doc ready append to participant table
    $(document).ready(function() {
        let element = `<tr>
                    <td colspan="4" class="text-center">
                        <div class="titleContent">{!! __('messages.no_selected_event_on_judge_message') !!}</div>
                    </td>
                </tr>`;
        $('#participant-table-body').append(element);
    });

    function resetTable() {
        $('#participant-table-body').empty();

        let element = `<tr>
                    <td colspan="4" class="text-center">
                        <div class="titleContent">{!! __('messages.no_event_message') !!}</div>
                    </td>
                </tr>`;
        $('#participant-table-body').append(element);
    }
    $('#filter-btn-group input[type="radio"]').on('change', function() {
        showLoading();
        var id_competition = $(this).val();
        loadRoundType(id_competition, initializeRoundOption);
    });

    function initializeRoundOption() {
        resetTable();
        // hide the run filter option
        $('#run-filter-container').hide();
        $('#round-options input[type="radio"]').on('change', function() {
            showLoading();
            var id_competition = $('#filter-btn-group input[type="radio"]:checked').val();
            var id_type = $(this).val();
            loadRunByIdCompetitionAndType(id_competition, id_type)
            // hideLoading();
        });
    }

    function initializeRunFilterOption() {
        resetTable();
        $('#run-options input[type="radio"]').on('change', function() {
            showLoading();

            var id_competition = $('#filter-btn-group input[type="radio"]:checked').val();
            var id_type = $('#round-options input[type="radio"]:checked').val();
            var id_games = $(this).val();

            loadParticipantByIdGames(id_competition, id_games, id_type);
            // loadGamesByIdCompetition(id_competition, loadRunByIdCompetitionAndType)
        });
    }

    function loadRunByIdCompetitionAndType(id_competition, id_type, callback) {
        showLoading();
        let url = '/api/event/get-games';
        let method = 'GET';
        let data = {
            id_competition: id_competition,
            id_type: id_type
        };
        let successCallback = function(response) {
            if(response) {
                $('#run-filter-container').show();
                let data = response.data;
                // append the data

                let gamesCount = 1;
                let filterTypeBtnGroup = $('#run-options');
                filterTypeBtnGroup.empty();
                data.forEach(function(round) {
                    filterTypeBtnGroup.append(`
                        <input type="radio" class="btn-check" name="run" id="run-${round.ID_games}" autocomplete="off" value="${round.ID_games}">
                        <label class="btn btn-white fw-bold" for="run-${round.ID_games}">
                            Run - ${gamesCount++}
                        </label>
                    `);
                });
                if(typeof initializeRunFilterOption === 'function') {
                    initializeRunFilterOption();
                }
                hideLoading();
            }
        };
        let errorCallback = function (xhr) {
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
        api(url, method, data, successCallback, errorCallback);
    }

    function loadRoundType(id_competition) {
        showLoading();
        let url = '/api/event/get-rounds';
        let method = 'GET';
        let data = {
            id_competition: id_competition
        };
        let successCallback = function(response) {
            if(response) {
                $('#round-filter-container').show();
                let data = response.data;
                // append the data
                let filterTypeBtnGroup = $('#round-options');
                filterTypeBtnGroup.empty();
                data.forEach(function(round) {
                    filterTypeBtnGroup.append(`
                        <input type="radio" class="btn-check" name="round" id="round-${round.ID_type}" autocomplete="off" value="${round.ID_type}">
                        <label class="btn btn-white fw-bold" for="round-${round.ID_type}">
                            ${round.ID_type == 1 ? 'Qualification' : (round.ID_type == 3 ? 'Final' : 'Semi Final')}
                        </label>
                    `);
                });
                if(typeof initializeRoundOption === 'function') {
                    initializeRoundOption();
                }
                hideLoading();
            }
        };
        let errorCallback = function (xhr) {
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
        api(url, method, data, successCallback, errorCallback);
    }

    function loadGamesByIdCompetition(id_competition, callback) {
        showLoading();
        let participantTableBody = $('#participant-table-body');
        participantTableBody.empty();

        let action = '/api/event/get-games/' + id_competition;
        let method = 'GET';
        data = null;
        successCallback = function(response) {
            hideLoading();
            if(response) {
                $('#round-filter-container').show();
                $('#run-filter-container').show();
                let data = response.data;
                // append the data
                let filterTypeBtnGroup = $('#round-options');
                let filterGameTypeOptions = $('#run-type-option');
                filterTypeBtnGroup.empty();
                filterGameTypeOptions.empty();
                let gameNumber = 1;
                data.forEach(function(game) {
                    // if game ID_type = 1 print Qualification, ID_type = 3 print Final
                    // make increment for the game number from ${game.ID_games}

                    filterTypeBtnGroup.append(`
                        <input type="radio" class="btn-check" name="round-type" id="type-${game.ID_type}" autocomplete="off" data-id-games="${game.ID_games}" value="${game.ID_type}">
                        <label class="btn btn-white fw-bold" for="round-type-${game.ID_type}">
                            ${game.ID_type == 1 ? 'Qualification' : 'Final'} - ${gameNumber++}
                        </label>
                    `);

                    if (typeof callback === 'function') {
                        callback();
                    }
                });

                gameNumber = 1;
                data.forEach(function(game) {
                    filterGameTypeOptions.append(`
                        <input type="radio" class="btn-check" name="type" id="type-${game.ID_type}" autocomplete="off" value="${game.ID_games}">
                        <label class="btn btn-white fw-bold" for="type-${game.ID_type}">
                            Run - ${gameNumber++}
                        </label>
                    `);
                });
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

    function loadParticipantByIdGames(id_competition, id_games, id_type) { // NOTE: separate by group if available
        showLoading();
        let action = '/api/event/get-participants';
        let method = 'GET';
        data = {
            id_competition: id_competition,
            id_games: id_games,
            id_type: id_type
        };
        successCallback = function(response) {
            hideLoading();
            if(response) {
                let data = response.data;
                $('#event-sport').text(data[0].event_name + ' - ' + (data[0].games_type == 1 ? 'Qualification' : 'Final'));
                $('#competition-name').text(data[0].competition_name);

                let participantTableBody = $('#participant-table-body');
                participantTableBody.empty();
                let no = 1;

                let currentGroup = null;
                
                data.forEach(async function(participant) {
                    if (participant.groups != '0') {
                        if (participant.groups !== currentGroup) {
                            currentGroup = participant.groups;
                            // Display the group header
                            participantTableBody.append(`
                                <tr class="group-header">
                                    <td colspan="4" class="text-center fw-bold">
                                        Group ${currentGroup}
                                    </td>
                                </tr>
                            `);
                        }
                    }
                    
                    // photo process
                    let profileUrl = participant.photoFile ? '/storage/user/' + participant.photoFile : '/assets/img/avatar400x400.jpg';
                    
                    let origin = participant.country_id == null ? 'Not Set by User' : (participant.country_id == 'ID' ? (participant.country_name + '<br />' + participant.indo_province_name + ', ' + participant.indo_city_name) : (participant.country_name + ', ' + participant.city_name)) ;
                    participantTableBody.append(`
                        <tr>
                            <td class="text-center">${no++}</td>
                            <td class="d-flex flex-start align-items-center">
                                <img src="${profileUrl}" height="50" alt="User Photo" class="user-photo-thumbnail me-3" />
                                <div>
                                    <span class="fw-bold">${participant.full_name} (${participant.age} YO)</span>
                                    <br />
                                    <small class="d-block text-capitalize">Stance: ${participant.stance == 1 ? 'Regular' : 'Goofy'}</small>
                                </div>
                            </td>
                            <td>
                                ${origin}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-red btn-red-sm" onclick="play(${participant.ID_contestant})">
                                    <i class="fas fa-play me-2"></i>
                                    Play
                                </button>
                            </td>
                        </tr>
                    `);
                });
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

    async function checkFileExists(filename) {
        try {
            let response = await fetch(filename);
            let data = await response.json();
            return data.exists;
        } catch (error) {
            console.error('Error checking file existence:', error);
            return false;
        }
    }
</script>
@stop