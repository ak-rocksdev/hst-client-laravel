@extends('layout.template')

@section('image-preview'){{ asset('assets/img/default-web-cover.jpg') }}@stop

@section('title')Events @stop

@section('description')Your Hyperscore Events Page. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('body')
<div class="container py-5">
    <div class="row">
        <!-- Events Title -->
        <div class="col-12 mb-4">
            <p class="fw-bold label label-title">{{ __('messages.events') }}</p>
            <h2 class="fw-bold section-title text-white">{{ __('messages.latestevent') }}</h2>
        </div>
        <div class="col-12 mb-4">
            <label for="competition" class="form-label me-3 text-white fw-bold text-uppercase">Filter</label>
            <div id="filter-btn-group" class="btn-group btn-group-container" role="group" aria-label="Radio toggle button group">
                <a class="btn btn-white fw-bold btn-filter {{ request('filter') == 'my' ? 'checked' : '' }}" href="?filter=my">My Events</a>
                <a class="btn btn-white fw-bold btn-filter {{ request('filter') == 'all' || !request('filter') ? 'checked' : '' }}" href="?filter=all">All Events</a>
            </div>
        </div>
        @foreach ($events as $event)
            @php
                $files = ['/competition/' . $event->ID_event . '/sbanner.png', '/competition/' . $event->ID_event . '/sbanner.jpg', '/competition/' . $event->ID_event . '/mbanner.jpg'];
                $defaultFile = '/competition/default-banner.jpg';
                $bannerUrl = asset('storage' . $defaultFile);
                foreach ($files as $file) {
                    if (Storage::disk('public')->exists($file)) {
                        $bannerUrl = asset('storage' . $file);
                        break;
                    }
                }
            @endphp
            <div class="col-12">
                <div class="row event-container-card mx-0 mb-4">
                    <div class="col-4 event-image">
                        <div class="square-wrapper">
                            <img src="{{ $bannerUrl }}" alt="About Us">
                        </div>
                    </div>
                    <div class="col-8 py-3 px-4">
                        <p class="mb-0 text-danger fs-5 fw-bold">{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }}</p>
                        <a class="no-link" href="/event/detail/{{ $event->ID_event }}">
                            <h3 class="fw-bold text-black mb-0">{{ $event->name }}</h3>
                        </a>
                        <p class="subcaption-section text-black">{{ $event->location }}</p>
                        <div class="d-block">
                            <a href="/event/detail/{{ $event->ID_event }}" class="button button-black">Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@stop