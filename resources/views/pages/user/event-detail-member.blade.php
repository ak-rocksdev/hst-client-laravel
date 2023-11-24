@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title')Event Detail @stop

@section('description')Your Hyperscore Event Detail Page. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('body')
<div class="jumbotron jumbotronImage" style="background-image: url();"> </div>
<div class="hero-image-container-gradient">
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

<div id="detail" class="container py-5">
    <div class="row mb-4">
        <div class="col-12 col-lg-6">
            <div class="eventInfo e_container">
                <div class="eventInfo e_header">
                    <h2>{{ $event->name }}</h2>
                    <div class="eventInfo e_subTitle">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <i class="far fa-clock me-2"></i> <span class="text-uppercase fw-bold">{{ __('messages.event_date') }}</span> 
                                <div>{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }}</div>
                            </div>
                            <div class="col-md-6 col-12">
                                <i class="fas fa-map-marker-alt me-2"></i><span class="text-uppercase fw-bold">{{ __('messages.event_location') }}</span> 
                                <div>{{ $event->location }}</div>
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
    </div>
</div>

<div class="container">
    <div class="row">
        @if ($isJudgeForThisEvent)
        <div class="col-12">
            <a href="/event/judge/{{ $event->ID_event }}" class="btn btn-danger mb-3">
                <i class="fa-solid fa-calculator me-3"></i>Go To Judging Area
            </a>
        </div>
        @endif
        <div class="col-12">
            <a href="/event/check-in/{{ $event->ID_event }}" class="btn btn-secondary mb-3">
                <i class="fa-solid fa-user-check me-2"></i>Check-In Area
            </a>
        </div>
    </div>
</div>

@stop