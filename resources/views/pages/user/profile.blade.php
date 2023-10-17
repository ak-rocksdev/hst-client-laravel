@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Profile @stop

@section('description')Hyperscore User Profile and Information @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

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
                            <!-- <i class="fas fa-star starLevel"></i>
                            <i class="far fa-star starLevel"></i>
                            <i class="far fa-star starLevel"></i>
                            <i class="far fa-star starLevel"></i>
                            <i class="far fa-star starLevel"></i> -->
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
                <div class="info-box hover-expand-effect" data-toggle="modal" data-target="#myModal">
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

        

        <!-- MODAL -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="qrcodeCanvas">
                            <h2 class="text-center text-dark">Your ID Code</h2>
                            <img class="d-block mx-auto" src="qr?code=">
                            <h4 class="text-center text-dark">ID Number: </h4>
                            <button type="button" class="btn center-block btn-block mx-auto" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop