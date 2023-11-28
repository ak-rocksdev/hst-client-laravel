@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Team Manager @stop

@section('description')Hyperscore Manage Team @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('body')
<!-- <div class="jumbotron jumbotron-fluid">
    <div class="jumbotronProfile" style="background-image: url( );"></div>
</div> -->
<div class="hero-image-container-gradient" style="padding: 0 !important; overflow: hidden;">
    <img class="project-main-image blur" src="{{ asset('assets/img/user-background-display.jpg') }}" alt="Event Poster">
    <div class="container hero-text-container">
        <div class="hero-text">
            <h1 class="text-white fw-bold">Team Manager Page</h1>
            <p>
                <i class="fa-solid fa-circle-info me-3"></i>Add your team here
            </p>
            <!-- <a href="#detail" class="button button-grey">Detail<i class="fas fa-chevron-right ms-3"></i></a> -->
        </div>
    </div>
</div>
<div class="container">
    <div class="row py-3">
        <div class="col-sm-12">
            <div class="d-flex justify-content-end gap-3">
                <span class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addExistingUserModal">
                    <i class="fa-solid fa-user-check me-2"></i>
                    Add Existing User
                </span>
                <!-- Register Event button show if checkbox checked -->
                <button id="register-event-button" type="button" class="btn btn-primary action-button" style="display: none;" data-bs-toggle="modal" data-bs-target="#registerUserToEventModal">
                    <i class="fa-solid fa-file-pen me-2"></i>
                    Register Event For Selected User
                </button>
                <!-- Delete User Button -->
                <button id="delete-user-button" type="button" class="btn btn-danger action-button" style="display: none;">
                    <i class="fa-solid fa-user-minus me-2"></i>
                    Delete Selected
                </button>
                <!-- create new user -->
                <!-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createNewUserModal">
                    <i class="fa-solid fa-user-plus me-2"></i>
                    Create New User
                </button> -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-header-gradient d-flex flex-row align-items-center p-3">
                <img class="me-3" src="{{ asset('assets/img/logo_light.png') }}">
                <div class="d-flex flex-column table-title-container">
                    <div id="event-sport" class="fw-bold fs-3">My Team List</div>
                    <div class="fw-normal">Manager: <span id="competition-name">{{ auth()->user()->full_name }}</span></div>
                </div>
            </div>
            <div class="table-container">
                <table class="table table-dark table-striped table-borderless text-uppercase">
                    <thead>
                        <tr>
                            <th class="text-center" width="8%"><span class="fw-bold">Select</span></th>
                            <th class="text-center" width="7%"><span class="fw-bold">NO.</span></th>
                            <th width="35%">
                                <span class="fw-bold">
                                    {{ __('messages.name') }}
                                </span>
                            </th>
                            <th width="20%"><span class="fw-bold">{{ __('messages.origin') }}</span></th>
                            <th width="30%" class="text-center"><span class="fw-bold">Action</span></th>
                        </tr>
                    </thead>
                    <tbody id="participant-table-body">
                        <!-- If $teams not available -->
                        @if($teams == null || $teams->count() == 0)
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <span class="titleContent">{!! __('messages.no_user_on_team') !!}</span>
                            </td>
                        </tr>
                        @endif
                        <!-- If $teams available -->

                        @if ($teams != null && $teams->count() > 0)
                            @foreach ($teams as $key => $team)
                            @php
                                $origin = $team->country_id == null ? 'Not Set by User' : ($team->country_id == 'ID' ? ($team->country_name . ' - ' . $team->indo_province_name . ', ' . $team->indo_city_name) : ($team->country_name . ', ' . $team->city_name));
                                
                                $team->photoFile == null ? $file1 = '-' : $file1 = '/user/' . $team->photoFile;
                                $file2 = '/user/' . $team->email . '.jpg';
                                $defaultFile = '/assets/img/avatar400x400.jpg';
                                if (Storage::disk('public')->exists($file1)) {
                                    $profileUrl = asset('storage' . $file1);
                                } else if (Storage::disk('public')->exists($file2)) {
                                    $profileUrl = asset('storage' . $file2);
                                } else{
                                    $profileUrl = asset($defaultFile);
                                }
                            @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <input name="select-user" type="checkbox" class="form-check-input" />
                                </td>
                                <td class="text-center align-middle">
                                    {{ $key + 1 }}
                                </td>
                                <td class="d-flex flex-start align-items-center">
                                    <img src="{{ $profileUrl }}" height="50" alt="User Photo" class="user-photo-thumbnail me-3" />
                                    <div>
                                        <span class="fw-bold">{{ $team->full_name }}</span>
                                        <br />
                                        <small class="d-block text-capitalize">{{ $origin }}</small>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    Dummy Origin
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-row justify-content-center align-items-center gap-2">
                                        <button class="btn btn-red btn-red-sm" data-bs-toggle="modal" data-bs-target="#registerUserToEventModal">
                                            <i class="fa-solid fa-file-pen"></i>
                                            <span class="ms-2">Register Event</span>
                                        </button>
                                        <button class="btn btn-red btn-red-sm" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                            <i class="fa-solid fa-user-edit"></i>
                                            <span class="visually-hidden">Edit</span>
                                        </button>
                                        <button class="btn btn-red btn-red-sm" onClick="">
                                            <i class="fa-solid fa-user-minus"></i>
                                            <span class="visually-hidden">Remove</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="addExistingUserModal" class="modal modal-lg fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-dark fw-bold">Add Existing User to Your Team</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 text-dark">
                <div class="container py-3">
                    <div class="input-group" style="height: 50px;">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Search" aria-describedby="searchButton">
                        <button class="btn btn-secondary" type="button" id="clearButton">Clear</button>
                    </div>
                    <small class="text-muted">Search by Name, Nick Name, Phone, Country, or City</small>
                    <div id="searchResultsContainer" class="mt-3"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="cancel-upload" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- registerUserToEventModal -->
<div id="registerUserToEventModal" class="modal modal-lg fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-dark fw-bold">Register Event</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 text-dark">
                <div class="container py-3">
                    Register Event
                </div>
            </div>
            <div class="modal-footer">
                <button id="cancel-upload" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        // add existing user
        $('#clearButton').click(function() {
            $('input[type="text"]').val('');
            $('#searchResultsContainer').empty();
            $('#clearButton').html('Clear');
        });
    });

    // Register Event button show if at least one select-user checkbox is checked
    $('input[name="select-user"]').change(function() {
        if ($('input[name="select-user"]:checked').length > 0) {
            $('.action-button').slideDown();
        } else {
            $('.action-button').slideUp();
        }
    });

    // on close modal simulate click clear button
    $('#addExistingUserModal').on('hidden.bs.modal', function() {
        $('#clearButton').click();
        // if there is loading-spinner on clear button, remove it
        $('#clearButton').html('Clear');
    });

    // get input value
    $('input[type="text"]').on('keyup', function() {
        var search = $(this).val();
        console.log(search == '' || search == null);
        if(search == '' || search == null) {
            setTimeout(() => {
                $('#clearButton').html('Clear');
            }, 500);
            $('#searchResultsContainer').empty();
        } else {
            getUserData(search);
        }
        // add loading-spinner to clear button, using html template
        let loadingSpinner = `
            <div class="d-flex justify-content-center">
                <div id="loading-spinner" class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;

        $('#clearButton').html(loadingSpinner);
    });

    // hit user API to get user data
    function getUserData(search) {
        var action = '/api/team/search-user?search=' + search;
        var method = 'GET';
        var form = {};
        var successCallback = function(response) {
            var searchResultsContainer = $('#searchResultsContainer');
            searchResultsContainer.empty();
            $('#clearButton').html('Clear');
            
            var searchResults = response.data;
            if(searchResults != null && searchResults.length > 0) {
                searchResults.forEach(function(item, index) {
                    let profileUrl = item.photoFile ? '/storage/user/' + item.photoFile : '/assets/img/avatar400x400.jpg';
                    let origin = item.country_id == null ? 'Origin Not Set by User' : (item.country_id == 'ID' ? (item.country_name + '<br />' + item.indo_province_name + ', ' + item.indo_city_name) : (item.country_name + ', ' + item.city_name));
                    searchResultsContainer.append(`
                        <div class="d-flex flex-row justify-content-between align-items-center border-bottom py-2">
                            <div class="d-flex flex-row align-items-center">
                                <img class="user-photo-thumbnail me-3" src="${profileUrl}" height="50" alt="User Photo" />
                                <div>
                                    <span class="fw-bold">${item.full_name}</span>
                                    <br />
                                    <small class="d-block text-capitalize">${origin}</small>
                                </div>
                            </div>
                            <button class="btn btn-primary" onClick="addUserToTeam(${item.ID_user})">
                                <i class="fa-solid fa-user-plus"></i>
                                <span class="visually-hidden">Add</span>
                            </button>
                        </div>
                    `);
                });
            } else {
                searchResultsContainer.append(`
                    <div class="d-flex flex-row justify-content-between align-items-center border-bottom py-2">
                        <div class="d-flex flex-row align-items-center">
                            <div>
                                <span class="fw-bold">No User Found</span>
                                <br />
                                <small class="d-block text-capitalize">Please try again with another keyword</small>
                            </div>
                        </div>
                    </div>
                `);
            }
        };
        var errorCallback = function(response) {
            console.log(response);
        };
        api(action, method, form, successCallback, errorCallback);
    }

    function addUserToTeam(userId) {
        var action = '/api/team/set-member';
        var method = 'POST';
        var form = {
            _token: '{{ csrf_token() }}',
            ID_user_member: userId
        };
        var successCallback = function(response) {
            $('#addExistingUserModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'User has been added to your team!',
                showConfirmButton: false,
                timer: 1500
            }).then((result) => {
                location.reload();
            });
        };
        var errorCallback = function(response) {
            console.log(response);
        };
        api(action, method, form, successCallback, errorCallback);
    }
</script>
@stop