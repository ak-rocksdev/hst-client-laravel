@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title') Events @stop

@section('description')HST (Hyper Score Technology) is an action sport management app, scoring system app, and administration in one complete system. It's a cloud based system that ensure the access on almost any internet connected devices will run well. Our services will define that to run the event can become so quick, easy, fun, and professional. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('body')
<div class="jumbotron jumbotronImage" style="background-image: url();"> </div>
<div class="mb-4">
    <img class="project-main-image" src="{{ asset('assets/img/event_bg.jpg') }}" alt="Event Poster">
</div>

<div class="container">
    <div class="row">
        <!-- Events Title -->
        <div class="col-12 mb-4">
            <p class="fw-bold label label-title">{{ __('messages.events') }}</p>
            <h2 class="fw-bold section-title text-white">{{ __('messages.latestevent') }}</h2>
        </div>
        @foreach ($events as $event)
        @php
            $file1 = '/competition/' . $event->ID_event . '/sbanner.png';
            $file2 = '/competition/' . $event->ID_event . '/sbanner.jpg';
            $file3 = '/competition/' . $event->ID_event . '/mbanner.jpg';
            $defaultFile = '/competition/default-banner.jpg';

            if (Storage::disk('public')->exists($file1)) {
                $bannerUrl = asset('storage' . $file1);
            } else if (Storage::disk('public')->exists($file2)) {
                $bannerUrl = asset('storage' . $file2);
            } else if (Storage::disk('public')->exists($file3)) {
                $bannerUrl = asset('storage' . $file3);
            } else {
                $bannerUrl = asset('storage' . $defaultFile);
            }
        @endphp
        <div class="col-12 col-lg-6">
            <div class="row event-container-card mx-0 mb-4">
                <div class="col-12 col-md-6 col-xl-4 event-image">
                    <div class="square-wrapper">
                        <img src="{{ $bannerUrl }}" alt="About Us" class="w-100 h-100">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-8 py-3 px-4">
                    <p class="mb-0 text-danger fs-5 fw-bold">{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }}</p>
                    <a class="no-link" href="#">
                        <h3 class="fw-bold text-black mb-0">{{ $event->name }}</h3>
                    </a>
                    <p class="subcaption-section text-black">{{ $event->location }}</p>
                    <div class="d-block">
                        <a href="/event/{{ $event->ID_event }}" class="button button-black">View Event</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@stop