@extends('layout.template')

@section('image-preview'){{ asset('assets/img/bg-image-hst-1.jpg') }}@stop

@section('title')Hyper Score Technology @stop

@section('description')HST (Hyper Score Technology) is an action sport management app, scoring system app, and administration in one complete system. It's a cloud based system that ensure the access on almost any internet connected devices will run well. Our services will define that to run the event can become so quick, easy, fun, and professional. @stop

@section('site-name')Hyper Score Technology @stop

@section('page-type')website @stop

@section('extracss')
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
/>
@stop

@section('body')
<div id="home" class="container-fluid position-relative">
    <div class="row">
        <div class="col-12 p-0 home-section">
            <div class="mobile-bg">
                <img src="{{ asset('assets/img/bg-image-hst.jpg') }}" alt="background" class="position-absolute top-50 start-50 translate-middle">
            </div>
            <div class="h-100">
                <div class="container-jumbotron bg-left h-100">
                    <div class="half-left-banner">
                        <div class="wrapper">
                            <header>
                                <h1>{{ __('messages.welcome') }}</h1>
                                <p>{{ __('messages.welcome_subtitle') }}</p>
                            </header>
                            <div class="d-block mb-4">
                                <a href="#services" class="button button-black">{{ __('messages.see_services') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="half-right-banner position-relative">
                        <!-- <figure>
                            <img src="{{ asset('assets/img/bg-image-hst.jpg') }}" alt="Big Skatepark Image" class="img-fluid">
                            <figcaption>
                                <p>ProJam International Festival</p>
                                <p class="subcaption">Kuta Beach - Bali</p>
                            </figcaption>
                        </figure> -->
                        <!-- Slider main container -->
                        <div class="swiper h-100">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                <div class="swiper-slide">
                                    <figure>
                                        <img src="{{ asset('assets/img/bg-image-hst.jpg') }}" alt="Big Skatepark Image" class="img-fluid">
                                        <figcaption>
                                            <p>ProJam International Festival</p>
                                            <p class="subcaption">Kuta Beach - Bali</p>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div class="swiper-slide">
                                    <figure>
                                        <img src="{{ asset('assets/img/bg-image-hst-1.jpg') }}" alt="Big Skatepark Image" class="img-fluid">
                                        <figcaption>
                                            <p>Young Guns Series</p>
                                            <p class="subcaption">Bogor - West Java</p>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div class="swiper-slide">
                                    <figure>
                                        <img src="{{ asset('assets/img/bg-image-hst-2.jpg') }}" alt="Big Skatepark Image" class="img-fluid">
                                        <figcaption>
                                            <p>Singapore Skateboarding Championship</p>
                                            <p class="subcaption">Singapore</p>
                                        </figcaption>
                                    </figure>
                                </div>
                                ...
                            </div>
                            <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>

                            <!-- If we need scrollbar -->
                            <div class="swiper-scrollbar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="clients" class="container-fluid">
    <div class="row">
        <div class="col-12 clients">
            <div class="d-flex justify-content-evenly py-4 align-items-center client-wrapper">
                <img src="{{ asset('/assets/img/clients/client-1.png') }}" alt="Client Logo" class="client-logo img-fluid">
                <img src="{{ asset('/assets/img/clients/client-2.png') }}" alt="Client Logo" class="client-logo img-fluid">
                <img src="{{ asset('/assets/img/clients/client-3.png') }}" alt="Client Logo" class="client-logo img-fluid">
                <img src="{{ asset('/assets/img/clients/client-4.png') }}" alt="Client Logo" class="client-logo img-fluid">
                <img src="{{ asset('/assets/img/clients/client-5.png') }}" alt="Client Logo" class="client-logo img-fluid">
            </div>
        </div>
    </div>
</div>
<div id="events" class="container-fluid event-section py-5">
    <div class="container">
        <div class="row">
            <!-- Events Title -->
            <div class="col-12 mb-4">
                <p class="fw-bold label label-title">{{ __('messages.events') }}</p>
                <h2 class="fw-bold section-title">{{ __('messages.latestevent') }}</h2>
            </div>
            @foreach ($events as $event)
            @php
                $file1 = '/competition/' . $event->ID_event . '/sbanner.png';
                $file2 = '/competition/' . $event->ID_event . '/sbanner.jpg';
                $defaultFile = '/competition/default-banner.jpg';

                if (Storage::disk('public')->exists($file1)) {
                    $bannerUrl = asset('storage' . $file1);
                } else if (Storage::disk('public')->exists($file2)) {
                    $bannerUrl = asset('storage' . $file2);
                } else{
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
                        <a class="no-link" href="#">
                            <h3 class="fw-bold text-black">{{ $event->name }}</h3>
                        </a>
                        <p class="mb-0 text-black">{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }} | {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</p>
                        <p class="subcaption-section text-black">{{ $event->location }}</p>
                        <div class="d-block">
                            <a href="/event/{{ $event->ID_event }}" class="button button-black">{{ __('messages.viewevent') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <!-- See More Event Button -->
            <div class="col-12">
                <div class="d-flex justify-content-center py-4">
                    <a href="/events" class="button button-black">{{ __('messages.seemoreevent') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="about" class="container py-5">
    <div class="row">
        <div class="col-12 col-md-4 col-lg-5">
            <div class="image-container">
                <img src="{{ asset('assets/img/about-us-1.jpg') }}" alt="About Us" class="about-us-thumb w-100">
            </div>
        </div>
        <div class="col-12 col-md-8 col-lg-7">
            <p class="fw-bold label label-title">{{ __('messages.about_us') }}</p>
            <h2 class="fw-bold text-white section-title">{{ __('messages.about_us_title') }}</h2>
            <p class="subcaption-section text-white">
                {!! __('messages.about_us_description') !!}
            </p>
            <div class="d-block mb-4">
                <a href="#" class="button button-black">{{ __('messages.more_about_us') }}</a>
            </div>
        </div>
    </div>
</div>
<div id="contact" class="container-fluid contact-container">
    <div class="row">
        <div class="col-12 p-0 bg-white">
            <div class="row m-0">
                <div class="col-12 col-lg-4 order-2 order-lg-1 p-0">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="wrapper-contact">
                            <p class="fw-bold label label-title">{{ __('messages.contact_us') }}</p>
                            <h2 class="fw-bold section-title">{{ __('messages.contact_us_description') }}</h2>
                            <div class="inline">
                                <i class="fa-solid fa-phone"></i>
                                <h3 class="fw-bold m-0">
                                    <a href="tel:+6281291720267" class="phone-link">+62 812 9172 0267</a>
                                </h3>
                            </div>
                            <div class="inline">
                                <i class="fa-solid fa-envelope"></i>
                                <h3 class="fw-bold m-0">mail@hyperscore.net</h3>
                            </div>
                            <p class="text-black">
                                {{ __('messages.weekdays')}} | 08.00 - 17.00</br>
                                GMT +7 (Jakarta Time)
                            </p>
                            <div class="d-flex button-container">
                                <a href="https://api.whatsapp.com/send?phone=6281291720267&text=Halo%20Hyperscore%20saya%20mau%20konsultasi%20tentang%20skatepark" class="btn btn-whatsapp" target="_blank">
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <span>{{ __('messages.chat_now') }}</span>
                                </a>
                                <a href="https://www.instagram.com/hyperscoretechnology" class="btn btn-black" target="_blank">
                                    <i class="fa-brands fa-instagram"></i>
                                    <span>{{ __('messages.follow_us') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8 order-1 order-lg-2 p-0">
                    <div class="contact-image-container h-100">
                        <img src="{{ asset('assets/img/contact-us-cover.jpg') }}" alt="Contact Us Cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        direction           : 'horizontal',
        loop                : true,
        watchSlidesProgress : true,
        loopPreventsSliding : false,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
            type: "progressbar",
        },

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },
    });
</script>
@stop