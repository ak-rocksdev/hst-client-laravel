<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ Request::is('/') ? '#' : '/' }}">
            <img src="{{ asset('assets/img/logo_light.png') }}" class="me-2" alt="Logo" height="50">
            <span class="fs-6">Hyperscore</span>
        </a>

        <!-- Toggler for mobile -->
        <div id="nav-button" class="nav-mobile-toggle">
            <div id="hamburger" class="hamburger">
                <svg width="35" height="35" viewBox="0 0 100 100">
                    <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" />
                    <path class="line line2" d="M 20,50 H 80" />
                    <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" />
                </svg>
            </div>
        </div>
        <!-- Navigation Menu -->
        <div class="navbar-toggle collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item navbar-logo-mobile">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ Request::is('/') ? '#' : '/' }}">
                        <img src="{{ asset('assets/img/logo_light.png') }}" class="me-2" alt="Logo" height="32">
                        <span class="text-white fs-6">Hyperscore</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ Request::is('/') ? 'active' : '' }}" href="{{ Request::is('/') ? '#' : '/' }}">{{ __('messages.home') }}</a>
                </li>
                <li class="nav-item">
                    <!-- <a class="nav-link fw-bold {{ Request::is('events*') ? 'active' : '' }}"
                        href="{{ Request::is('events*') ? '/#events' : '/#events' }}">
                        {{ __('messages.events') }}
                    </a> -->
                    <a class="nav-link fw-bold {{ Request::is('event*') ? 'active' : '' }}"
                        href="{{ Auth::check() ? (Request::is('events*') ? '#' : '/events') : (Request::is('event*') ? '/event/'.request('id').'#' : '/#events') }}">
                        {{ __('messages.events') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ Request::is('/') ? '#about' : '/#about' }}">{{ __('messages.about_us') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ Request::is('/') ? '#services' : '/#services' }}">{{ __('messages.services') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ Request::is('/') ? '#contact' : '/#contact' }}">{{ __('messages.contact_us') }}</a>
                </li>
                @if(Auth::guard('web')->check() != '1')
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ Request::is('login') ? 'active' : '' }}" href="{{ Request::is('login') ? '#' : '/login' }}">{{ __('messages.login') }}</a>
                </li>
                @endif
            </ul>
        </div>

        <!-- Language Dropdown Desktop -->
        @if(Auth::guard('web')->check() == '1')
        <!-- Add <i class="fa-solid fa-bell"></i> -->
        <div class="dropdown d-block me-3">
            <div class="btn-dropdown d-flex flex-row" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bell"></i>
                @if($newNotificationsCount > 0)
                <span class="badge notification-count rounded-pill">{{ $newNotificationsCount }}</span>
                @endif
            </div>
            <ul class="dropdown-menu dropdown-menu-left p-0 slide-down notification-container" aria-labelledby="notificationDropdown">
                <!-- When there is No Notification, Show Text -->
                @if($notifications == null || $notifications->count() == 0)
                <li>
                    <span class="notification-empty dropdown-item">
                        <!-- Bell Icon Big -->
                        <i class="fa-solid fa-bell fa-3x text-secondary-subtle"></i>
                        No New Notification
                    </span>
                </li>
                @else
                @foreach($notifications as $notification)
                <li data-id="{{ $notification->id }}" class="{{ $notification->read_at == null ? 'new' : '' }}">
                    <a class="dropdown-item mb-1 py-2" href="#">
                        <div class="d-flex flex-row align-items-center">
                            <div class="p-2 rounded-circle bg-success-subtle notification-icon me-2">
                                <i class="fa-solid fa-newspaper fa-lg"></i>
                            </div>
                            <div class="d-flex flex-column notification-text-container">
                                <h5 class="fw-bold {{ $notification->read_at == null ? 'new' : '' }}">{{ $notification->title }}</h5>
                                <span>{{ $notification->description }}</span>
                                <!-- count how many hours ago based on created_at -->
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
                @endif
                <!-- "See all notification" text, centered and bold -->
                <li>
                    <a class="dropdown-item py-2 text-center fw-bold" href="#">
                        See All Notifications
                    </a>
                </li>
            </ul>
        </div>
        <li class="nav-item dropdown d-block">
            <span class="d-flex align-items-center justify-content-evenly dropdown-toggle text-white {{ Request::is('profile') ? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="far fa-user-circle fa-lg me-2"></i><span class="me-2">user</span>
            </span>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ Request::is('profile') ? '#' : '/profile' }}">
                    {{ __('messages.profile') }}
                </a>
                <a class="dropdown-item" href="{{ url('/logout') }}">
                    {{ __('messages.logout') }}
                </a>
            </div>
        </li>
        @else
        <div class="dropdown d-block">
            <button class="btn-dropdown" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-globe"></i>
                <span class="ms-2">{{ !session('lang') ? 'EN' : (session('lang') == 'id' ? 'ID' : 'EN') }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-left slide-down" aria-labelledby="languageDropdown">
                <li><a class="dropdown-item" href="/set-language/en">English</a></li>
                <li><a class="dropdown-item" href="/set-language/id">Indonesia</a></li>
            </ul>
        </div>
        @endif
    </div>
</nav>

@push('scripts')
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#notificationDropdown').on('click', function() {
            let newNotificationCount = $('.notification-container li.new').length;
            if (newNotificationCount > 0) {
                console.log(newNotificationCount)
                var notificationIds = [];
                $('.notification-container li.new').each(function() {
                    let notificationId = $(this).data('id');
                    let thisHasNewClass = $(this).hasClass('new');
                    
                    if(thisHasNewClass) {
                        if(notificationId) {
                            notificationIds.push(notificationId);
                        }
                    }
                });
                action = '/api/notification/set-as-read';
                method = 'GET';
                form = {
                    ID_notification: notificationIds
                };
                successCallback = function(response) {
                    countUnreadNotification();
                    setTimeout(function() {
                        $('.notification-text-container h5').removeClass('new');
                        $('.notification-container li').removeClass('new');
                    }, 1000);
                };
                errorCallback = function(response) {
                };
                console.log(form);
                api(action, method, form, successCallback, errorCallback)
            }
        });
    });
</script>
@endpush